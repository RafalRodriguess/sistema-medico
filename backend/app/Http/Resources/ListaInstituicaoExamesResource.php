<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListaInstituicaoExamesResource extends JsonResource
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
            'grupo' => $this->grupo,
            'exame' => $this->exame,
            'convenio' => $this->convenio,
            'instituicao' => $this->instituicao_id,
            'instituicao_nome' => $this->instituicao,
            'valor' => $this->valor,
        ];
    }

}
