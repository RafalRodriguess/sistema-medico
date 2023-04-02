<?php

namespace App\Http\Resources;

use App\UsuarioCartao;
use App\AgendamentoProcedimento;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProcedimentosCollection;

class AgendamentosExamesUsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'data' => $this->DataFormat(),
            'status' => $this->status,
            'valor_total' => $this->valor_total,
            'parcelas' => $this->parcelas,
            'porcento_parcela' => $this->porcento_parcela,
            'free_parcelas' => $this->free_parcelas,
            'cartao' => $this->Cartao(),
            'instituicao' => new InstituicaoResource($this->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->instituicao),
            'procedimento' => $this->ProcedimentosConvenio(),
            'outrosProcedimento' => $this->outrosProcedimentos(),
            'grupo' => new GruposProcedimentosResource($this->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->grupoProcedimento),
            'forma_pagamento' => $this->forma_pagamento,
        ];
    }

    private function Cartao()
    {
        $cartao = UsuarioCartao::withTrashed()->where('id', $this->cartao_id)->first();

        if($cartao){
            return $cartao;
        }

        return null;
    }

    private function DataFormat()
    {
        return date('d/m/Y H:i', strtotime($this->data));
    }

    private function outrosProcedimentos(){
        $transacao = $this->codigo_transacao;
        $outros = AgendamentoProcedimento::whereHas('agendamentos',function($q) use ($transacao){
            $q->where('codigo_transacao', $this->codigo_transacao)
            ->where('id','<>', $this->id);
        })->get();
        $grupos = (object)[];
        $procedimentos = [];
        foreach ($outros as $agendamentoProcedimento) {
            $aux = [
                'procedimento' => $agendamentoProcedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento,
                'convenio'=> $agendamentoProcedimento->procedimentoInstituicaoConvenio->convenios,
                'valor' => $agendamentoProcedimento->valor_atual,
                'grupo_nome' => $agendamentoProcedimento->agendamentos->instituicoesAgenda->grupos ? $agendamentoProcedimento->agendamentos->instituicoesAgenda->grupos->grupo->nome  : null
            ];
            array_push($procedimentos, $aux);
        }
        foreach ($procedimentos as $procedimento) {
            if(!property_exists($grupos,$procedimento['grupo_nome'])){
                $grupos->{$procedimento['grupo_nome']} = [];
            }
            array_push($grupos->{$procedimento['grupo_nome']},$procedimento);
        }
        return $grupos;
    }

    private function ProcedimentosConvenio()
    {
        $procedimentos = [];
        foreach ($this->agendamentoProcedimento as $agendamentoProcedimento) {
            $aux = [
                'procedimento' => $agendamentoProcedimento->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento,
                'convenio'=> $agendamentoProcedimento->procedimentoInstituicaoConvenio->convenios,
                'valor' => $agendamentoProcedimento->valor_atual
            ];
            array_push($procedimentos, $aux);
        }

        return $procedimentos;
    }

    private function Convenio()
    {
        $convenio = [
            'id' => $this->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->id,
            'nome' => $this->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->convenios->nome,
        ];

        return $convenio;
    }
}
