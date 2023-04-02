<?php

namespace App\Http\Resources;

use App\Comercial;
use App\EnderecoEntrega;
use App\FretesRetiradaHorario;
use App\PedidoProduto;
use App\PedidoProdutoPergunta;
use App\UsuarioCartao;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoDetalhesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'valor_total' => $this->valor_total,
            'forma_entrega' => $this->forma_entrega,
            'data_entrega' => $this->data_entrega ? $this->data_entrega->format('d/m/Y') : null,
            'status_pedido' => $this->status_pedido,
            'observacao' => $this->observacao,
            'prazo_tipo' => $this->prazo_tipo,
            'prazo_tipo_minimo' => $this->prazo_tipo_minimo,
            'prazo_tipo_maximo' => $this->prazo_tipo_maximo,
            'prazo_maximo' => $this->prazo_maximo,
            'prazo_minimo' => $this->prazo_minimo,
            'comercial' => $this->Comercial(),
            'produtosPedido' => $this->PedidoProduto(),
            'parcelas' => $this->parcelas,
            'valor_parcela' => $this->valor_parcela,
            'free_parcela' => $this->free_parcela,
            'valor_entrega' => $this->valor_entrega,
            'codigo_transacao' => $this->codigo_transacao,
            'enderecoPedido' => $this->EnderecoPedido(),
            'cartoes_id' => $this->Cartao(),
            'data_pedido' => $this->created_at->format('d/m/Y H:i'),
            'entregue' => $this->verificaEntrega(),
            'entregue_comercial' => $this->entrega_comercial,
            'forma_pagamento' => $this->forma_pagamento,
        ];
    }

    private function Comercial()
    {
        $comercial = Comercial::where('id', $this->comercial_id)->first();

        return new ComercialResource($comercial);
    }

    private function PedidoProduto()
    {
        $produtoPedido = PedidoProduto::where('pedido_id', $this->id)->get();
            foreach ($produtoPedido as $key => $value) {
                $produtoPedido[$key]['alternativa'] = PedidoProdutoPergunta::where('pedido_produtos_id', $value->id)->get();
            }
        return $produtoPedido;
    }

    private function EnderecoPedido()
    {
        $enderecoPedido = EnderecoEntrega::withTrashed()->where('id', $this->endereco_entregas_id)->first();
        if($enderecoPedido->frete_id_retirada){
            $enderecoPedido['horarios'] = FretesRetiradaHorario::withTrashed()->where('retirada_id', $enderecoPedido->frete_id_retirada)->get();
        }
        return $enderecoPedido;
    }

    private function Cartao()
    {
        $cartao = UsuarioCartao::withTrashed()->where('id', $this->cartoes_id)->first();

        if($cartao){
            return $cartao;
        }

        return null;
    }

    private function verificaEntrega()
    {
        if($this->entrega_sistema == null && $this->entrega_cliente == null){
            return true;
        }

        return false;
    }


}
