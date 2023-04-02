<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
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
            'id' => $this['id'],
            'hora_inicio' => $this['hora_inicio'],
            'hora_fim' => $this['hora_fim'],
            'hora_intervalo' => $this['hora_intervalo'],
            'duracao_intervalo' => $this['duracao_intervalo'],
            'duracao_atendimento' => $this['duracao_atendimento'],
            'agendamentos' => $this->getAgendamentos(),
        ];
    }

    private function getAgendamentos()
    {

        if (sizeof($this['agendamentos']) > 0) {
            foreach ($this['agendamentos'] as $key => $value) {
                $agendamentos[] = [
                    'dataAgendada' => $value->data,
                ];
            }
            
            return $agendamentos;
        }

        return null;
    }
}
