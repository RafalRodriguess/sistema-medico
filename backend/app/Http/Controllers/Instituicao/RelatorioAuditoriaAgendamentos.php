<?php

namespace App\Http\Controllers\Instituicao;

use App\AuditoriaAgendamento;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioAuditoria\PesquisarRelatorioAuditoriaRequest;
use App\Instituicao;
use Illuminate\Http\Request;

class RelatorioAuditoriaAgendamentos extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_auditoria_agendamento');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $usuarios =  $instituicao->instituicaoUsuarios()->orderBy('nome', 'asc')->get();
        $status = ['pendente', 'agendado', 'confirmado', 'cancelado', 'finalizado', 'excluir', 'ausente', 'em_atendimento', 'finalizado_medico'];
        
        return view('instituicao.relatorios.auditoria_agendamentos.index', \compact('status', 'usuarios', 'instituicao'));
    }

    public function tabela(PesquisarRelatorioAuditoriaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_auditoria_agendamento');
        $dados = $request->validated();

        $auditorias = AuditoriaAgendamento::getRelatorioAuditoria($dados)->get();
        $auditorias->loadMissing('usuarios', 'agendamentos', 'agendamentos.instituicoesAgenda', 'agendamentos.instituicoesAgenda.prestadores', 'agendamentos.instituicoesAgenda.prestadores.prestador', 'agendamentos.agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
        'agendamentos.pessoa');

        return view('instituicao.relatorios.auditoria_agendamentos.tabela', \compact('auditorias'));
    }
}
