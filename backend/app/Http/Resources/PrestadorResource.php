<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrestadorResource extends JsonResource
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
            'id' => $this->id,
            'agenda' => $this->getAgenda(),
            'procedimentos' => $this->prestadoresProcedimentos ? $this->getProcedimentos() : null,
        ];
    }

    private function getAgenda()
    {
        
        if ($this->agenda) {
            $agenda = [
                'id' => $this->agenda['id'],
                'hora_inicio' => $this->agenda['hora_inicio'],
                'hora_fim' => $this->agenda['hora_fim'],
                'hora_intervalo' => $this->agenda['hora_intervalo'],
                'duracao_intervalo' => $this->agenda['duracao_intervalo'],
                'duracao_atendimento' => $this->agenda['duracao_atendimento'],
                'agendamentos' => $this->getAgendamentos(),
            ];        
    
            return $agenda;
        }

        return null;
    }

    private function getAgendamentos()
    {

        if (sizeof($this->agendamentos) > 0) {
            foreach ($this->agendamentos as $key => $value) {
                $agendamentos[] = [
                    'dataAgendada' => $value->data,
                ];
            }
            
            return $agendamentos;
        }

        return null;
    }

    private function getProcedimentos()
    {
        if (sizeof($this->prestadoresProcedimentos) > 0) {
            
            foreach ($this->prestadoresProcedimentos as $key => $value) {
                
                if($value->procedimentos){
                    $procedimentos[$value->procedimentos->id] = [
                        'id' => $value->procedimentos->id,
                        'descricao' => $value->procedimentos->descricao,
                    ];
                }
                        
            }
            
            return $procedimentos;
        }

        return null;
    }
}
