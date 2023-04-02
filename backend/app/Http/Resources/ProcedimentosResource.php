<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProcedimentosResource extends JsonResource
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
            'descricao' => $this->descricao,
            'convenios' => $this->getConvenios(),
        ];
    }

    private function getConvenios()
    {
        foreach($this->convenios as $value)
        {
            $convenios[] = [
                'convenio_id' => $value->pivot->convenios_id,
                'id' => $value->id,
                'valor' => $value->pivot->valor,
                'nome' => $value->nome,
            ];
        }

        return $convenios;
    }
}
