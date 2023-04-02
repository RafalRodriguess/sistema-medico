<?php

namespace App\Http\Livewire\Comercial;

use App\Pedido;
use App\PedidoMensagem;
use App\Comercial;
use App\Usuario;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class PedidosMensagensPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';
    private  $itens;
    private  $usuario;
    private  $pedido;
    private  $comercial;
    
    public function mount(Request $request)
    {
        $this->pesquisa = $request->get('pedido');
  
    }

    public function render()
    {
        config(["auth.defaults.guard" => "comercial"]);

        $this->performQuery();
        $this->alreadyReady();
       
        return view('livewire.comercial/pedidos-mensagens-pesquisa', [
            'itens' => $this->itens,
            'usuario' => $this->usuario,
            'pedido' => $this->pedido,
            'comercial' => $this->comercial,
        ]);
    }

    private function performQuery(): void
    {
      
        $mensagens = PedidoMensagem::where('pedido_id', $this->pesquisa);
        
        $Querypedido = Pedido::where('id', $this->pesquisa)->get()->first();
        
        $queryUsuario = Usuario::where('id', $Querypedido->usuarios_id)->get()->first();
        
        $queryComercial = Comercial::where('id', $Querypedido->comercial_id)->get()->first();

        $this->itens = $mensagens->paginate(5000);

        $this->usuario = $queryUsuario;

        $this->pedido = $Querypedido;

        $this->comercial = $queryComercial;

        
    }


    private function alreadyReady(){
        PedidoMensagem::where(['pedido_id' => $this->pesquisa, 'remetente' => 'cliente', 'visto' => 0])->update(array('visto' => 1, 'data_visto'=>date('Y-m-d H:i:s')));
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
