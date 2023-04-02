<?php

namespace App\Http\Controllers\Instituicao;

use App\ContaPagar;
use App\Convenio;
use App\GruposProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioOdontologico\PesquisarOdontologicoGrupoRequest;
use App\Instituicao;
use App\OdontologicoPaciente;
use App\Procedimento;
use Illuminate\Http\Request;

class RelatorioOdontologicoGrupo extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_odontologico_grupo');

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
        $grupos = GruposProcedimentos::whereHas('procedimentos_instituicoes', function($q) use($instituicao){
            $q->where('instituicao_id', $instituicao->id);
            $q->whereHas('procedimento', function($query){
                $query->where('odontologico', 1);
            });
        })->get();
            
        return view('instituicao.relatorios_odontologicos.odontologico_grupo.index', \compact('contas', 'negociadores', 'avaliadores', 'formasPagamento', 'convenios', 'instituicao', 'grupos'));
    }

    public function tabela(PesquisarOdontologicoGrupoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_odontologico_grupo');
        $dados = $request->validated();

        $orcamentos = OdontologicoPaciente::getDemonstrativoOdontologicoGrupo($dados)->get();
        foreach ($orcamentos as $key => $value) {
            $value->parcelas = $value->contaReceber()->getParcelasOdontologicas($dados)->get();
            $value->total_procedimentos = $value->itens()->getTotalProcedimentos($dados)->sum('valor');
        }

        return view('instituicao.relatorios_odontologicos.odontologico_grupo.tabela', \compact('orcamentos'));
    }

    public function getProcedimentoOdontologicosPesquisa(Request $request)
    {
        if ($request->ajax())
        {
            $grupo = $request->input('grupo');
            
            $instituicao = $request->session()->get('instituicao');
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            $procedimentos = Procedimento::getProcedimentoOdontologicoGrupoPesquisaModel($nome, $instituicao, $grupo)->simplePaginate(100);

            $morePages=true;
            if (empty($procedimentos->nextPageUrl())){
                $morePages=false;
            }

            $results = array(
                "results" => $procedimentos->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }
    }
}
