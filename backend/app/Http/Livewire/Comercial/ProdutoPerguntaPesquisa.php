<?php

namespace App\Http\Livewire\Comercial;

use App\Produto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ProdutoPerguntaPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $tipo = '';

    public $produto;

    private $perguntas;
    
    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'tipo' => ['except' => ''],
    ];
    
    public function mount(Produto $produto)
    {
        $this->produto = $produto;
    }

    public function render()
    {
        config(["auth.defaults.guard" => "comercial"]);
        $this->authorize('habilidade_comercial_sessao', 'visualizar_perguntas');

        $this->performQuery();

        return view('livewire.comercial.produto-pergunta-pesquisa',[
            'produto' => $this->produto,
            'perguntas' => $this->perguntas
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->produto->produto_perguntas()
                    ->search($this->pesquisa, $this->tipo);
        $this->perguntas = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    public function updatingTipo(): void
    {
        $this->resetPage();
    }
}
