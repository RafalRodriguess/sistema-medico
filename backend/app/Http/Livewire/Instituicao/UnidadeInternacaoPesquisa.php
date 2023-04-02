<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class UnidadeInternacaoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    
    public $pesquisa = '';

    public $instituicao;

    protected $unidades_internacoes;

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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_unidade_internacao');
        $this->performQuery();
        return view('livewire.instituicao.unidade-internacao-pesquisa', [
            'unidades_internacoes' => $this->unidades_internacoes,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->unidadesInternacoes()->search($this->pesquisa);
        $this->unidades_internacoes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}
