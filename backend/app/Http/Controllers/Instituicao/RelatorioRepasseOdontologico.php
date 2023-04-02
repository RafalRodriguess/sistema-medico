<?php

namespace App\Http\Controllers\Instituicao;

use App\ContaReceber;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioOdontologico\PesquisarRepasseOdontologicoRequest;
use App\Instituicao;
use App\OdontologicoItemPaciente;
use App\OdontologicoPaciente;
use App\Procedimento;
use Illuminate\Http\Request;

class RelatorioRepasseOdontologico extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_repasse_odontologico');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $prestadores = $instituicao->medicos()->orderBy('nome', 'asc')->get();
        $formas_pagamento = ContaReceber::formas_pagamento();
        $convenios = $instituicao->convenios()->orderBy('nome', 'asc')->get();
        
        return view('instituicao.relatorios_odontologicos.repasse_odontologico.index', \compact('prestadores', 'instituicao', 'formas_pagamento', 'convenios'));
    }

    public function tabela(PesquisarRepasseOdontologicoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_repasse_odontologico');
        $dados = $request->validated();

        $itens = OdontologicoItemPaciente::getRepasseOdontologico($dados)->get();

        $orcamentos = [];
        foreach ($itens as $key => $value) {
            if(array_key_exists($value->odontologico->id, $orcamentos)){
                $value->desconto_total = $orcamentos[$value->odontologico->id]['desconto_total'];
            }else{
                $orcamento = OdontologicoPaciente::find($value->odontologico->id);
                if($orcamento->desconto > 0){
                    $desconto = $orcamento->desconto / count($orcamento->itens);
                    $orcamentos[$value->odontologico->id]['desconto_total'] = $desconto;
                    $value->desconto_total = $desconto;
                }else{
                    $value->desconto_total = 0.00;
                    $orcamentos[$value->odontologico->id]['desconto_total'] = 0.00;
                }
            }
        }

        return view('instituicao.relatorios_odontologicos.repasse_odontologico.tabela', \compact('itens'));
    }

    public function getProcedimentoOdontologicosPesquisa(Request $request)
    {
        if ($request->ajax())
        {
            $instituicao = $request->session()->get('instituicao');
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            $procedimentos = Procedimento::getProcedimentoOdontologicoPesquisaModel($nome, $instituicao)->simplePaginate(100);

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
