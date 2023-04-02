<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\AtestadoPaciente;
use App\ConclusaoPaciente;
use App\EncaminhamentoPaciente;
use App\ExamePaciente;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\LaudoPaciente;
use App\Pessoa;
use App\ProntuarioPaciente;
use App\ReceituarioPaciente;
use App\RefracaoPaciente;
use App\RelatorioPaciente;
use Illuminate\Http\Request;

class ResumosPaciente extends Controller
{
    public function pacienteResumo(Request $request, Pessoa $paciente)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_resumo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        // abort_unless($agendamento->pessoa_id === $paciente->id, 403);

        $user = $request->user('instituicao');
        $tipo_resumo = 1;
        $resumos = $paciente->agendamentoResumo($user->id)->orderBy('data', 'DESC')->get();

        if(count($user->prestadorMedico()->get()) > 0){
            $tipo_resumo = $user->prestadorMedico[0]->resumo_tipo;
            
            if ($tipo_resumo == 2) {
                return view('instituicao.prontuarios.resumo.lista_aberta', \compact('resumos', 'tipo_resumo'));
            }
        }
        
        return view('instituicao.prontuarios.resumo.lista', \compact('resumos', 'tipo_resumo'));
    }

    public function prontuario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ProntuarioPaciente $prontuario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_prontuario');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $prontuario->agendamento_id, 403);

        $obs = null;
        if(array_key_exists('obs', $prontuario->prontuario)){
            $obs = str_replace("\n", '<br>', $prontuario->prontuario['obs']);
        }
        
        return view('instituicao.prontuarios.resumo.resumoProntuario', \compact('prontuario', 'agendamento', 'obs'));
    }
    
    public function receituario(Request $request, Agendamentos $agendamento, Pessoa $paciente, ReceituarioPaciente $receituario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_receituario');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $receituario->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoReceituario', \compact('receituario', 'agendamento'));
    }

    public function atestado(Request $request, Agendamentos $agendamento, Pessoa $paciente, AtestadoPaciente $atestado)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atestado');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $atestado->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoAtestado', \compact('atestado', 'agendamento'));
    }

    public function relatorio(Request $request, Agendamentos $agendamento, Pessoa $paciente, RelatorioPaciente $relatorio)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $relatorio->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoRelatorio', \compact('relatorio', 'agendamento'));
    }

    public function exame(Request $request, Agendamentos $agendamento, Pessoa $paciente, ExamePaciente $exame)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_exame');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $exame->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoExame', \compact('exame', 'agendamento'));
    }
    
    public function refracao(Request $request, Agendamentos $agendamento, Pessoa $paciente, RefracaoPaciente $refracao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_refracao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $refracao->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoRefracao', \compact('refracao', 'agendamento'));
    }

    public function encaminhamento(Request $request, Agendamentos $agendamento, Pessoa $paciente, EncaminhamentoPaciente $encaminhamento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_encaminhamento');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $encaminhamento->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoEncaminhamento', \compact('encaminhamento', 'agendamento'));
    }

    public function laudo(Request $request, Agendamentos $agendamento, Pessoa $paciente, LaudoPaciente $laudo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_laudo');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $laudo->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoLaudo', \compact('laudo', 'agendamento'));
    }

    public function conclusao(Request $request, Agendamentos $agendamento, Pessoa $paciente, ConclusaoPaciente $conclusao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_conclusao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $conclusao->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoConclusao', \compact('conclusao', 'agendamento'));
    }
}
