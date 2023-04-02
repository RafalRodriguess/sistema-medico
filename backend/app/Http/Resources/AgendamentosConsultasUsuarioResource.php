<?php

namespace App\Http\Resources;

use App\UsuarioCartao;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendamentosConsultasUsuarioResource extends JsonResource
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
            'instituicao' => new InstituicaoResource($this->instituicoesAgenda->prestadores->instituicao),
            'prestador' => new PrestadorResource($this->instituicoesAgenda->prestadores->prestador),
            'especialidade' => new EspecialidadeResource($this->instituicoesAgenda->prestadores->especialidade),
            'procedimento' => $this->Procedimento(),
            'convenio' => $this->Convenio(),
            'forma_pagamento' => $this->forma_pagamento
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

    private function Procedimento()
    {
        $procedimento = [
            'descricao' => $this->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->descricao,
            'id' => $this->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->id,
            'tipo' => $this->agendamentoProcedimento[0]->procedimentoInstituicaoConvenio->procedimentoInstituicao->procedimento->tipo,
        ];

        return $procedimento;
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
