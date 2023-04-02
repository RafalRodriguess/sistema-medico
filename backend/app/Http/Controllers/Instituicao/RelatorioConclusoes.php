<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\ConclusaoPaciente;
use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioConclusoes\TabelaRelatorioConclusoesRequest;
use App\Instituicao;
use App\Pessoa;
use Illuminate\Http\Request;

class RelatorioConclusoes extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_conclusao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $motivos = $instituicao->motivosConclusao()->get();

        $profissionais = $instituicao->medicosRelatorioAtendimentos()->get();
        $profissionais->load('prestadoresInstituicoes');
        
        return view('instituicao.relatorios.conclusao.lista', \compact('profissionais', 'motivos', 'instituicao'));
    }
    
    public function tabela(TabelaRelatorioConclusoesRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_conclusao');
        $dados = $request->validated();

        $conclusoes = ConclusaoPaciente::getTabela($dados)->get();

        return view('instituicao.relatorios.conclusao.tabela', \compact('conclusoes'));
    }

    public function conclusao(Request $request, Agendamentos $agendamento, Pessoa $paciente, ConclusaoPaciente $conclusao)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $paciente->instituicao_id, 403);
        abort_unless($agendamento->pessoa_id === $paciente->id, 403);
        abort_unless($agendamento->id === $conclusao->agendamento_id, 403);

        return view('instituicao.prontuarios.resumo.resumoConclusao', \compact('conclusao', 'agendamento'));
    }
}
