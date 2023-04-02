<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\WithPagination;

class EspecializacoesPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    private $especializacoes;
    public $instituicao;
    public $pesquisa = '';

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_especializacao');
        $this->performQuery(); 
        return view('livewire.instituicao.especializacoes-pesquisa', [
            'especializacoes' => $this->especializacoes,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->especializacoes()->search($this->pesquisa);
        $this->especializacoes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
    
    
}
