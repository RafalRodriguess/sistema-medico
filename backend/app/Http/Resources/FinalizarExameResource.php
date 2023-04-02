<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FinalizarExameResource extends JsonResource
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
            'instituicao' => new InstituicaoResource($this),
            'procedimento' => $this->procedimento(),
            'convenio' => $this->convenio(),
            'grupo' => $this->grupo(),
        ];
    }

    private function procedimento()
    {
        return [
            'descricao' => $this->procedimentos[0]->descricao,
            'id' => $this->procedimentos[0]->pivot->procedimentos_id,
        ];
    }
    
    private function convenio()
    {
        return [
            'nome' => $this->procedimentos[0]->procedimentoInstituicao[0]->instituicaoProcedimentosConvenios[0]->nome,
            'convenio_id' => $this->procedimentos[0]->procedimentoInstituicao[0]->instituicaoProcedimentosConvenios[0]->pivot->convenios_id,
            'valor' => $this->procedimentos[0]->procedimentoInstituicao[0]->instituicaoProcedimentosConvenios[0]->pivot->valor,
        ];
    }
    
    private function grupo()
    {
        return [
            'nome' => $this->procedimentos[0]->procedimentoInstituicao[0]->grupoProcedimento->nome,
            'id' => $this->procedimentos[0]->procedimentoInstituicao[0]->grupoProcedimento->id,
            
        ];
    }
}
