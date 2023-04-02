<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;

class RelatoriosConciliacaoCartao extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_cartao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        // $convenios = $instituicao->convenios()->get();
        // $procedimentos = $instituicao->procedimentos()->get();
        // $profissionais = $instituicao->medicos()->get();
        // $status = ['pendente', 'agendado', 'confirmado', 'cancelado', 'finalizado', 'excluir', 'ausente'];
        // $grupos = GruposProcedimentos::get();
        // $setores = $instituicao->setoresExame()->get();
        // $solicitantes = $instituicao->solicitantes()->get();

        return view('instituicao.relatorios.conciliacao_cartao.lista', \compact('convenios', 'procedimentos', 'profissionais', 'status', 'grupos', 'setores', 'instituicao', 'solicitantes'));
    }

    public function tabela(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_cartao');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->input();

        

        $cartoes = $instituicao->contasReceber()
            ->whereBetween('data_vencimento', [$dados['data_inicio'], $dados['data_fim']])
            ->whereIn('forma_pagamento', ['cartao_credito','cartao_debito'])
            ->with([
                'paciente' => function ($q){
                    $q->withTrashed();
                },
                'agendamentos.instituicoesAgenda.prestadores' => function ($q){
                    $q->withTrashed();
                },
                'odontologico.prestador' => function ($q){
                    $q->withTrashed();
                }
            ])
        ->get();
        
        return view('instituicao.relatorios.conciliacao_cartao.tabela', \compact('cartoes'));
    }
}
