<?php

namespace App\Http\Controllers\API;

use App\Agendamentos;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgendamentosConsultasUsuarioCollection;
use App\Http\Resources\AgendamentosConsultasUsuarioResource;
use App\Http\Resources\AgendamentosExamesUsuarioCollection;
use App\Http\Resources\AgendamentosExamesUsuarioResource;
use Illuminate\Http\Request;

class AgendamentosUsuarioController extends Controller
{
    public function consultasUsuario(Request $request)
    {
        $agendamentos = Agendamentos::where('usuario_id', $request->user('sanctum')->id)->whereHas('instituicoesAgenda', function($qHas){
            $qHas->where('referente', 'prestador');
        })->with(['instituicoesAgenda' => function($query) {
                $query->where('referente', 'prestador');
                $query->with(['prestadores.instituicao','prestadores.prestador','prestadores.especialidade']);
            }, 'agendamentoProcedimento' => function($qAgendaProcedimento) {
                $qAgendaProcedimento->with(['procedimentoInstituicaoConvenio' => function($qProcedimentoConvenio) {
                    $qProcedimentoConvenio->with(['procedimento','convenios']);
                }]);
        }])->orderBy('id', 'desc')->paginate(10);

        return new AgendamentosConsultasUsuarioCollection($agendamentos);
    }

    public function agendamentoConsultaDetalhesUsuario(Request $request, Agendamentos $agendamento)
    {
        if($request->user('sanctum')->id == $agendamento->usuario_id){
            $agendamento->load([
                'instituicoesAgenda.prestadores.instituicao',
                'instituicoesAgenda.prestadores.prestador',
                'instituicoesAgenda.prestadores.especialidade',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimento',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
            ]);

            return new AgendamentosConsultasUsuarioResource($agendamento);
        }

        return [
            'data' => null
        ];
    }

    public function agendamentosExamesUsuario(Request $request)
    {
        $agendamentos = Agendamentos::where('usuario_id', $request->user('sanctum')->id)
        ->whereHas('instituicoesAgenda', function($qHas){
            $qHas->where('referente', 'procedimento');
            $qHas->orWhere('referente', 'grupo');
        })->with([
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.instituicao',
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
            'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.grupoProcedimento'])->orderBy('id', 'desc')->paginate(10);
        return new AgendamentosExamesUsuarioCollection($agendamentos);
    }

    public function agendamentoExameDetalhesUsuario(Request $request,Agendamentos $agendamento)
    {
        if($request->user('sanctum')->id == $agendamento->usuario_id){
            $agendamento->load([
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.instituicao',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.grupoProcedimento',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.procedimentoInstituicao.procedimento',
                'agendamentoProcedimento.procedimentoInstituicaoConvenio.convenios',
            ]);

            return new AgendamentosExamesUsuarioResource($agendamento);
        }

        return [
            'data' => null
        ];

    }
}
