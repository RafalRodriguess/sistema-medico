<?php

namespace App\Http\Livewire\Instituicao;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use App\ApresentacaoConvenio;
use App\Instituicao;
use Illuminate\Http\Request;

class ApresentacoesConveniosPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';

    private $apresentacoes;

    public $instituicao;
    
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
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_apresentacoes_convenio');
        $this->performQuery();

        return view('livewire.instituicao.apresentacoes-convenios-pesquisa', [
            'apresentacoes' => $this->apresentacoes
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->apresentacaoConvenios()->search($this->pesquisa);
        $this->apresentacoes = $query->paginate(15);
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage();
    }
}

