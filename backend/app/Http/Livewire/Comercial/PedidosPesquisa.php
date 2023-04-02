<?php

namespace App\Http\Livewire\Comercial;


use App\Pedido;
use App\Comercial;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Libraries\PagarMe;



class PedidosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    public $status = '';

    public $unreadMessages = 0;

    public $comercial;
    public $saldo;
    public $saldo_a_receber;

    private  $pedidos;

    private  $numerosPedidos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'status' => ['except' => '0'],
    ];

    public function mount(Request $request)
    {

        $this->comercial = Comercial::find($request->session()->get('comercial'));

        $pagarMe = new PagarMe();
        if($this->comercial->id_recebedor){
            $saldo = $pagarMe->saldoRecebedor($this->comercial->id_recebedor);
            $this->saldo = $saldo->available->amount;
            $this->saldo_a_receber = $saldo->waiting_funds->amount;
        }else{
            $this->saldo = 0;
            $this->saldo_a_receber = 0;
        }
    }

    public function render()
    {
        config(["auth.defaults.guard" => "comercial"]);
        $this->authorize('habilidade_comercial_sessao', 'visualizar_pedidos');

        $this->performQuery();



        return view('livewire.comercial/pedidos-pesquisa', [
            'pedidos' => $this->pedidos,
            'numerosPedidos' => $this->numerosPedidos,
            'unreadMessages' => $this->unreadMessages,
            'saldo' => $this->saldo,
            'saldo_a_receber' => $this->saldo_a_receber,
        ]);
    }

    private function performQuery(): void
    {
        $query = Pedido::SearchLista($this->pesquisa, $this->status, $this->comercial, $this->unreadMessages);

        $qtdPedidos = DB::table('pedidos')->where('comercial_id', $this->comercial->id)->selectRaw('count(*) as total')
            ->selectRaw("count(case when status_pedido = 'pendente' then 1 end) as pendente")
            ->selectRaw("count(case when status_pedido = 'aprovado' then 1 end) as aprovado")
            ->selectRaw("count(case when status_pedido = 'cancelado' then 1 end) as cancelado")
            ->selectRaw("count(case when status_pedido = 'entregue' then 1 end) as entregue")
            ->selectRaw("count(case when status_pedido = 'disponivel' then 1 end) as disponivel")
            ->selectRaw("count(case when status_pedido = 'enviado' then 1 end) as enviado")
            ->get()->first();

        $this->numerosPedidos = $qtdPedidos;

        $this->pedidos = $query->paginate(15);
    }



    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }


    public function unreadMessages(): void
    {
        $this->unreadMessages = 1;
        $this->resetPage();
    }

    public function getAll(){
        $this->unreadMessages = 0;

        $this->resetPage();
    }


}
