<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioOdontologico\PesquisarOrcamentosConcluidosRequest;
use App\Instituicao;
use App\OdontologicoPaciente;
use Illuminate\Http\Request;

class RelatorioOrcamentosConcluidos extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_concluidos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $contas = $instituicao->contas()->get();
        
        return view('instituicao.relatorios_odontologicos.orcamentos_concluidos.index', \compact('contas', 'instituicao'));
    }

    public function tabela(PesquisarOrcamentosConcluidosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_concluidos');
        $dados = $request->validated();

        $orcamentos = OdontologicoPaciente::getOrcamentosConcluidos($dados)->get();

        return view('instituicao.relatorios_odontologicos.orcamentos_concluidos.tabela', \compact('orcamentos'));
    }
}
