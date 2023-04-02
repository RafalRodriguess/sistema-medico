<?php

namespace App\Http\Controllers\Instituicao;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\Pessoa;
use App\RegraCobranca;
use Illuminate\Http\Request;

class ContasAmbulatorial extends Controller
{
    public function index()
    {
        // $this->authorize('habilidade_instituicao_sessao', 'visualizar_contas_ambulatorial');

        return view('instituicao.contas_ambulatorial.criar');
    }

    public function getAgendamentos(Request $request, Pessoa $pessoa)
    {
        abort_unless($request->session()->get('instituicao') === $pessoa->instituicao_id, 403);

        $agendamento = $pessoa->agendamentos()->whereIn('status', ['finalizado', 'finalizado_medico'])->with('instituicoesAgenda', 'instituicoesAgenda.prestadores', 'instituicoesAgenda.prestadores.prestador')->whereHas('instituicoesAgenda')->whereHas('carteirinha')->orderBy('data', 'DESC')->get();

        return response()->json($agendamento);
    }

    public function getDadosAgendamentos(Request $request, Agendamentos $agendamento)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($agendamento->pessoa->instituicao_id === $instituicao->id, 403);
        $agendamento->load('carteirinha', 'carteirinha.convenio', 'carteirinha.planoUnico', 'carteirinha.planoUnico.regraCobranca', 'carteirinha.planoUnico.regraCobranca.itens');

        $procedimentos = $agendamento->agendamentoProcedimento()->getIdProcedimentos()->get();
        foreach ($procedimentos as $key => $value) {
            $idsProcedimento[] = $value->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->id;
        }

        $procedimentos_atendimento = $instituicao->procedimentoAtendimentos()->getRegraItens($agendamento, $idsProcedimento)->first();

        foreach ($procedimentos_atendimento->procedimento as $key => $value) {
            foreach ($agendamento->carteirinha->planoUnico->regraCobranca->itens as $key => $item) {
                if($item->grupo_procedimento_id == $value->procedimentoInstituicaoId->grupoProcedimento->id){
                    
                    $faturamento = $item->faturamento()->first();
                    $procedimento = $faturamento->procedimentos()->where('procedimento_id', $value->cod)->where('ativo', 1)->first();

                    if($procedimento){
                        if($item->base == "honorario"){
                            $base = $procedimento->vl_honorario;
                        }else if($item->base == "operacional"){
                            $base = $procedimento->vl_operacao;
                        }else{
                            $base = $procedimento->vl_total;
                        }
                        
                        $value->porcento_proc = number_format($base, 2, '.','.');
                        $value->porcento_pago = $item->pago;
                        
                    }
                }
            }
        }

        return view('instituicao.contas_ambulatorial.dados_agendamentos', \compact('agendamento', 'procedimentos_atendimento'));
    }
}
