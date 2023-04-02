<?php

namespace App\Http\Livewire\Instituicao;

use App\EstoqueInventarioProdutos;
use App\EstoqueEntradas;
use App\EstoqueInventario;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class EstoqueInventarioProduto extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';



    public  $id_entrada;

    public $estoqueEntrada;
    public $estoqueInventario;

    private $estoqueInventarioProdutos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(EstoqueInventario $estoqueInventario)
    {
        $this->estoqueInventario = $estoqueInventario;
    }

    public function render()
    {
       config(["auth.defaults.guard" => "instituicao"]);
       $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_entrada');
       $this->performQuery();

       return view('livewire.instituicao.estoque-inventario-produto', [
           'estoqueInventarioProdutos' => $this->estoqueInventarioProdutos,
           'estoqueInventario' => $this->estoqueInventario
       ]);
    }

    private function performQuery()
    {
        $query = $this->estoqueInventario->estoqueInventarioProdutos()->search($this->pesquisa);
        $this->estoqueInventarioProdutos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
