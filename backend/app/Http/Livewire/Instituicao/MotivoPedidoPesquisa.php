<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;  

class MotivoPedidoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $motivo_pedidos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    
    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivo_pedido');
        $this->performQuery();  
        return view('livewire.instituicao.motivo-pedido-pesquisa', [
            'motivo_pedidos' => $this->motivo_pedidos,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->motivo_pedidos()->search($this->pesquisa);
        $this->motivo_pedidos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

}
