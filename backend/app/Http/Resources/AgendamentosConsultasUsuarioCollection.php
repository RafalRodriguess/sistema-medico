<?php

namespace App\Http\Resources;

use App\Agendamentos;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AgendamentosConsultasUsuarioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function(Agendamentos $agendamento) {
                return new AgendamentosConsultasUsuarioResource($agendamento);
            })
        ];
    }
}
