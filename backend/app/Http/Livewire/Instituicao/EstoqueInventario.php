<?php

namespace App\Http\Livewire\Instituicao;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Instituicao;

class EstoqueInventario extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';
    public $instituicao;
    private  $estoqueInventario;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_inventario');
        $this->performQuery();
        $usuario_logado = request()->user('instituicao');

        return view('livewire.instituicao.estoque-inventario', [
            'estoqueInventario' => $this->estoqueInventario,
            'usuario_logado' => $usuario_logado
        ]);
    }

    private function performQuery(): void
    {

        $query = $this->instituicao->estoqueInventario()->search($this->pesquisa);

        $this->estoqueInventario = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
