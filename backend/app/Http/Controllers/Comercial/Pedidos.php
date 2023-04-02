<?php

namespace App\Http\Controllers\Comercial;

use App\Pedido;
use App\Comercial;
use App\PedidoMensagem;
use App\Usuario;
use App\EnderecoEntrega;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\PagarMe;

class Pedidos extends Controller
{

    public function index()
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_pedidos');

        return view('comercial.pedidos/lista');
    }

    public function getModalProdutos(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_pedidos');

        $pedido = Pedido::find($request->pedido);

        $endereco = EnderecoEntrega::where('id', '=', $pedido->endereco_entregas_id)->get()->first();

        return view('comercial.pedidos/modalProdutos', compact('pedido', 'endereco'));
    }

    public function getModalMensagens(Request $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'visualizar_pedidos');

        $pedido = Pedido::find($request->pedido);

        $comercial = Comercial::find($request->session()->get('comercial'));

        return view('comercial.pedidos/modalMensagens', compact('pedido', 'comercial'));
    }

    public function enviaMensagem(Request $request, Pedido $pedido)
    {

        $mensagem = $request->get('mensagem');

        //check message
        $checkmsg = str_replace(' ', '', $mensagem);
        if (strlen($checkmsg) > 0) {
            $msg = array(
                'remetente' => 'comercial',
                'mensagem' => $mensagem,
            );
            $retorno = $pedido->mensagem()->create($msg);
            event(new \App\Events\NotificacaoFCM(
                'Mensagem do Pedido #'.$pedido->id,
                $mensagem,
                'tabs/tab1/pedidosUsuario/mensagem/'.$pedido->id,
                $pedido->usuarios_id
            ));
            return $retorno->toJson();
        } else {
            return json_encode(FALSE);
        }
    }

    public function alteraStatus(Request $request, Pedido $pedido)
    {

        $valor = $request->get('acao');
        switch ($valor) {
            case 'aprovar':
                $this->aprovarPedido($pedido);
                break;
            case 'cancelar':
                $this->cancelarPedido($pedido);
                break;
            case 'enviar':
                $this->enviarPedido($pedido);
                break;
            case 'entregar':
                $this->entregarPedido($pedido);
                break;
            case 'disponibilizar':
                $this->disponibilizarPedido($pedido);
                break;
        }
    }


    private function aprovarPedido(Pedido $pedido)
    {
        $aceitos = ['pendente'];
        if (in_array($pedido->status_pedido, $aceitos)) {
            $pedido->update(['status_pedido' => 'aprovado']);
            event(new \App\Events\PedidoTimeline($pedido,"Pedido aprovado!",$pedido->comercial,json_encode($pedido->getChanges())));
            event(new \App\Events\NotificacaoFCM(
                'Pedido #'.$pedido->id,
                'O seu pedido foi aprovado pela loja!',
                'tabs/tab1/pedidosUsuario/'.$pedido->id,
                $pedido->usuarios_id
            ));
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }


    private function cancelarPedido(Pedido $pedido)
    {
        $aceitos = ['pendente', 'aprovado', 'entregue', 'disponivel', 'enviado'];
        if (in_array($pedido->status_pedido, $aceitos)) {

            $pagarMe = new PagarMe();
            $estorno = $pagarMe->estornarTransacaoComercial($pedido );

            if($estorno->status=='refunded'){
                $pedido->update(['status_pedido' => 'cancelado', 'status_pagamento' => 'estornado']);
                event(new \App\Events\PedidoTimeline($pedido,"Pedido cancelado!",$pedido->comercial,json_encode($pedido->getChanges())));
                event(new \App\Events\NotificacaoFCM(
                    'Pedido #'.$pedido->id,
                    'A loja cancelou o seu pedido!',
                    'tabs/tab1/pedidosUsuario/'.$pedido->id,
                    $pedido->usuarios_id
                ));
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }


        } else {
            echo json_encode(false);
        }
    }


    private function enviarPedido(Pedido $pedido)
    {
        $aceitos = ['aprovado'];
        if (in_array($pedido->status_pedido, $aceitos)) {
            $pedido->update(['status_pedido' => 'enviado']);
            event(new \App\Events\PedidoTimeline($pedido,"Pedido enviado!",$pedido->comercial,json_encode($pedido->getChanges())));
            event(new \App\Events\NotificacaoFCM(
                'Pedido #'.$pedido->id,
                'O seu pedido saiu para entrega!',
                'tabs/tab1/pedidosUsuario/'.$pedido->id,
                $pedido->usuarios_id
            ));
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }


    private function entregarPedido(Pedido $pedido)
    {
        $aceitos = ['disponivel', 'enviado'];
        if (in_array($pedido->status_pedido, $aceitos)) {

            if($pedido->entrega_cliente){
                $pedido->update(['status_pedido' => 'entregue', 'entrega_comercial' => date('Y-m-d H:i:s')]);
                event(new \App\Events\PedidoTimeline($pedido,"Pedido entregue!",$pedido->comercial,json_encode($pedido->getChanges())));
            }else{
                $pedido->update(['entrega_comercial' => date('Y-m-d H:i:s')]);
                event(new \App\Events\PedidoTimeline($pedido,"Loja confirmou entrega do pedido!",$pedido->comercial,json_encode($pedido->getChanges())));
                event(new \App\Events\NotificacaoFCM(
                    'Pedido #'.$pedido->id,
                    'Pedido confirmado pela loja, confirme que recebeu ou mande uma mensagem questionando!',
                    'tabs/tab1/pedidosUsuario/'.$pedido->id,
                    $pedido->usuarios_id
                ));
            }

            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }


    private function disponibilizarPedido(Pedido $pedido)
    {
        $aceitos = ['aprovado'];
        if (in_array($pedido->status_pedido, $aceitos)){
            $pedido->update(['status_pedido' => 'disponivel']);
            event(new \App\Events\PedidoTimeline($pedido,"Pedido disponibilizado",$pedido->comercial,json_encode($pedido->getChanges())));
            event(new \App\Events\NotificacaoFCM(
                'Pedido #'.$pedido->id,
                'O seu pedido esta disponivel para saque!',
                'tabs/tab1/pedidosUsuario/'.$pedido->id,
                $pedido->usuarios_id
            ));
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }


}
