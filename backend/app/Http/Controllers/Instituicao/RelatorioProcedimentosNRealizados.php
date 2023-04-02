<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioOdontologico\PesquisaProcedimentosNRealizadosRequest;
use App\Instituicao;
use App\OdontologicoPaciente;
use Illuminate\Http\Request;

class RelatorioProcedimentosNRealizados extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_procedimentos_nao_realizados');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $contas = $instituicao->contas()->get();
        
        return view('instituicao.relatorios_odontologicos.procedimentos_n_realizados.index', \compact('contas', 'instituicao'));
    }

    public function tabela(PesquisaProcedimentosNRealizadosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_procedimentos_nao_realizados');
        $dados = $request->validated();

        $orcamentos = OdontologicoPaciente::getProcedimentosNRealizados($dados)->get();

        return view('instituicao.relatorios_odontologicos.procedimentos_n_realizados.tabela', \compact('orcamentos'));
    }
}
