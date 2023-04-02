<?php

namespace App\Http\Livewire\Instituicao;

use App\Instituicao;
use App\Especialidade;
use App\Agendamentos as Agendamento;
use App\InstituicoesAgenda;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use carbon\carbon;

class AgendamentosRegistroPesquisa extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $pesquisa = '';
    public $nome = '';

    public $agenda;
    public $instituicao;
    public $medico = false;
    private $agendamentos_geral;


    protected $updatesQueryString = [
        'pesquisa' => ['except' => '']
    ];

    public function mount(Request $request, $dados)
    {
        if($dados['pesquisa']){
            $this->pesquisa = $dados['pesquisa'];
        }
        $this->instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuario = $request->user('instituicao');
        if($usuario->prestadorMedico()->first()){
            $this->medico = true;
        }

    }

    public function render()
    {
        $star = microtime(true);
        
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_agendamentos');

        $this->performQuery();
        // dd($this->agendamentos_geral->toArray());
        $view = view('livewire.instituicao.agendamentos-registro-pesquisa', [
            'agendamentos_geral' => $this->agendamentos_geral,
            'medico' => $this->medico,
        ]);

        $end = microtime(true);
        // dump($end - $star);
        return $view;
    }

    private function performQuery(): void
    {
        // $query= Agendamento::whereHas('usuario')
        // ->with('instituicoesAgenda.setor')
        $query= Agendamento::orderBy('data','desc')
            ->whereNotNull('instituicoes_agenda_id')
            ->whereNotNull('pessoa_id')
            ->searchByInstituicao($this->pesquisa, $this->instituicao->id)
            ->with([
                'instituicoesAgenda',
                'instituicoesAgenda.prestadores',
                'instituicoesAgenda.prestadores.prestador',
                'agendamentoProcedimento',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
                'pessoa',
                'contaReceber',
            ]);

       
        $this->agendamentos_geral = $query->paginate(15, ['*'], 'registroPage');

        // dd($this->agendamentos_geral->toArray());
    }

    public function updatingPesquisa(): void
    {
        $this->resetPage('registroPage');
    }

}
