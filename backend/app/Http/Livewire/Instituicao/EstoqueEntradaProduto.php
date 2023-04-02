<?php

namespace App\Http\Livewire\Instituicao;

use App\EstoqueEntradaProdutos;
use App\EstoqueEntradas;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class EstoqueEntradaProduto extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';



    public  $id_entrada;

    public $estoqueEntrada;

    private $estoqueEntradaProdutos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(EstoqueEntradas $estoqueEntrada)
    {
        $this->estoqueEntrada = $estoqueEntrada;
    }

    public function render()
    {
       config(["auth.defaults.guard" => "instituicao"]);
       $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_entrada');
       $this->performQuery();

       return view('livewire.instituicao.estoque-entrada-produto', [
           'estoqueEntradaProdutos' => $this->estoqueEntradaProdutos,
           'estoqueEntrada' => $this->estoqueEntrada
       ]);
    }

    private function performQuery()
    {
        $query = $this->estoqueEntrada->estoqueEntradaProdutos()->search($this->pesquisa)->orderBy('id', 'desc');
        $this->estoqueEntradaProdutos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
