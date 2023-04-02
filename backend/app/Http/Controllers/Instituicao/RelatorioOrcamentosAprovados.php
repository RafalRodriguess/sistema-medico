<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioOdontologico\PesquisarOrcamentosAprovadosRequest;
use App\Instituicao;
use App\OdontologicoPaciente;
use Illuminate\Http\Request;

class RelatorioOrcamentosAprovados extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_aprovados');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $negociadores =  $instituicao->instituicaoUsuarios()->orderBy('nome', 'asc')->get();
        
        return view('instituicao.relatorios_odontologicos.orcamentos_aprovados.index', \compact('instituicao', 'negociadores'));
    }

    public function tabela(PesquisarOrcamentosAprovadosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos_aprovados');
        $dados = $request->validated();

        $orcamentos = OdontologicoPaciente::getOrcamentosAprovados($dados)->get();
        $orcamentos->loadMissing('paciente', 'negociador');

        return view('instituicao.relatorios_odontologicos.orcamentos_aprovados.tabela', \compact('orcamentos'));
    }
}
