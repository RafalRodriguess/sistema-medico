<?php

namespace App\Http\Livewire\Instituicao;
 
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
 

class ProdutosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $especie = 0;

    public $classe = 0;

    public $generico = 2;

    public $mestre = 2;
    
    public $kit = 2;

    public $tipo = '';

    public $instituicao;

    private $produtos;

    protected $updatesQueryString = [
        'pesquisa' => ['except' => ''],
        'especie' => ['except' => 0],
        'classe' => ['except' => 0],
        'generico' => ['except' => 2],
        'mestre' => ['except' => 2],
        'kit' => ['except' => 2],
        'tipo' => ['except' => '']
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_produtos');
        $this->performQuery(); 
        $especies = $this->instituicao->especies()->get(); 
        $classes = $this->instituicao->classes()->get(); 

        return view('livewire.instituicao.produtos-pesquisa', [
            'produtos' => $this->produtos,
            'especies' => $especies,
            'classes' => $classes,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->produtos()
            ->search($this->pesquisa,$this->especie,$this->classe,$this->generico,$this->mestre,$this->kit,$this->tipo )
            ->orderBy('produtos.id', 'desc');
        $this->produtos = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }

    public function updatingEspecie(): void
    {
        $this->resetPage();
    }

    public function updatingClasse(): void
    {
        $this->resetPage();
    } 
    public function updatingGenerico(): void
    {
        $this->resetPage();
    } 
    public function updatingMestre(): void
    {
        $this->resetPage();
    } 
    public function updatingKit(): void
    {
        $this->resetPage();
    } 
    public function updatingTipo(): void
    {
        $this->resetPage();
    } 
   
}
