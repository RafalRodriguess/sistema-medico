<?php

namespace App\Http\Livewire\Instituicao;

use App\EstoqueBaixa;
use App\Http\Controllers\Instituicao\EstoqueBaixaProdutos;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Instituicao;
use App\ProdutoBaixa;

class ProdutosBaixaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';
    public $instituicao;
    public $estoqueBaixa;
    private  $produtosBaixa;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
    ];

    public function mount(EstoqueBaixa $estoqueBaixa)
    {
        $this->estoqueBaixa = $estoqueBaixa;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_entrada');
        $this->performQuery();

        return view('livewire.instituicao.produtos-baixa-pesquisa', [
            'produtosBaixa' => $this->produtosBaixa,
            'estoqueBaixa' => $this->estoqueBaixa
        ]);
    }

    private function performQuery(): void
    {

        $query = $this->estoqueBaixa->estoqueBaixaProdutos()->search($this->pesquisa);

        $this->produtosBaixa = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
