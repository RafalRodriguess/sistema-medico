<?php

namespace App\Http\Livewire\Instituicao;
use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class EstoquesPesquisa extends Component
{

    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    private $estoques;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoques');
        $this->performQuery(); 
        return view('livewire.instituicao.estoques-pesquisa', [
            'estoques' => $this->estoques,
        ]);
    }
 
    private function performQuery(): void
    {
        $query = $this->instituicao->estoques()->search($this->pesquisa);
        $this->estoques = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
