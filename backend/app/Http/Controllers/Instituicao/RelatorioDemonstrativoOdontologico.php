<?php

namespace App\Http\Controllers\Instituicao;

use App\ContaPagar;
use App\ContaReceber;
use App\Convenio;
use App\Especialidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioOdontologico\PesquisarDemonstrativoOdontologicoRequest;
use App\Instituicao;
use App\OdontologicoPaciente;
use Illuminate\Http\Request;

class RelatorioDemonstrativoOdontologico extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_demonstrativo_odontologico');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $contas = $instituicao->contas()->get();
        $negociadores = $instituicao->instituicaoUsuarios()->get();
        $avaliadores =  $instituicao->instituicaoUsuarios()->orderBy('nome', 'asc')->get();
        $formasPagamento = ContaPagar::formas_pagamento();
        $convenios = Convenio::where('instituicao_id', $instituicao->id)->with(['getProcedimentoConvenioInstuicao' => function($q) {
            $q->whereHas('procedimento', function($query){
                $query->where('odontologico', 1);
            });
            $q->with(['procedimento' => function($query){
                $query->where('odontologico', 1);
            }]);
        }])->whereHas('getProcedimentoConvenioInstuicao.procedimento', function($q) {
            $q->where('odontologico', 1);
        })->get();
        
        return view('instituicao.relatorios_odontologicos.demonstrativo_odontologico.index', \compact('contas', 'negociadores', 'avaliadores', 'formasPagamento', 'convenios', 'instituicao'));
    }

    public function tabela(PesquisarDemonstrativoOdontologicoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_demonstrativo_odontologico');
        $dados = $request->validated();

        $orcamentos = OdontologicoPaciente::getDemonstrativoOdontologico($dados)->get();
        foreach ($orcamentos as $key => $value) {
            $value->total = $value->contaReceber()->getParcelasOdontologicas($dados)->get();
            $value->parcelas = $value->contaReceber()->getParcelasOdontologicas($dados)->where('status', 1)->get();
            $value->parcelasReceber = $value->contaReceber()->getParcelasOdontologicas($dados)->where('status', 0)->get();
        }
        
        $contasPagar = [];

        if(array_key_exists('mostrar_conta_pagar', $dados)){
            $contasPagar = ContaPagar::getContasPagar($dados)->get();
        }

        return view('instituicao.relatorios_odontologicos.demonstrativo_odontologico.tabela', \compact('orcamentos', 'contasPagar'));
    }
}
