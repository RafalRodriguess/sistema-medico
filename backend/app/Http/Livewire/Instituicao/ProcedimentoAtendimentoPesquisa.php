<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class ProcedimentoAtendimentoPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $convenio = 0;

    public $plano = 0;

    public $instituicao;

    private  $procedimentos;

    protected $updatesQueryString = [
        'convenio' => ['except' => 0],
        'plano' => ['except' => 0],
    ];

    public function mount(Request $request)
    {
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
    }

    public function render()
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_procedimentos_atendimentos');

        $this->performQuery();

        $convenios = $this->instituicao->convenios()->get();
        
        return view('livewire.instituicao.procedimento-atendimento-pesquisa', [
            'procedimentos' => $this->procedimentos,
            'convenios' => $convenios,
        ]);
    }

    private function performQuery(): void
    {
        $query = $this->instituicao->procedimentoAtendimentos()->search($this->convenio, $this->plano);

        $this->procedimentos = $query->paginate(15);
    } 

    public function updatingConvenio(): void
    {
        $this->plano = 0;
        $this->resetPage();
    }
    public function updatingPlano(): void
    {
        $this->resetPage();
    }
}
