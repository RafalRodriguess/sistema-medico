<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelatorioOdontologico\PesquisarOrcamentosRequest;
use App\Instituicao;
use App\OdontologicoPaciente;
use Illuminate\Http\Request;

class RelatorioOrcamentos extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $negociadores =  $instituicao->instituicaoUsuarios()->orderBy('nome', 'asc')->get();
        
        return view('instituicao.relatorios_odontologicos.orcamentos.index', \compact('negociadores', 'instituicao'));
    }

    public function tabela(PesquisarOrcamentosRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_orcamentos');
        $dados = $request->validated();

        $orcamentos = OdontologicoPaciente::getOrcamentos($dados)->get();
        $orcamentos->loadMissing('paciente', 'avaliador', 'responsavel', 'negociador');

        foreach ($orcamentos as $key => $value) {
            if ($value->status == 'criado' || $value->status == 'reprovado') {
                $total = 0;
                foreach ($value->itens as $key => $item) {
                    if ($item->procedimento_instituicao_convenio_id){
                        $total += $item->procedimentos->valor;
                        $total += $item->desconto;
                    }
                }

                $value->valor_total = $total;
            }
        }

        return view('instituicao.relatorios_odontologicos.orcamentos.tabela', \compact('orcamentos'));
    }
}
