<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConvenioResource extends JsonResource
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
            'nome' => $this->nome,
            'id' => $this->pivot->id,
            'convenio_id' => $this->id,
            'valor' => $this->pivot->valor,
        ];
    }
}
