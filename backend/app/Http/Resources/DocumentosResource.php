<?php

namespace App\Http\Resources;
use Illuminate\Support\Str;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentosResource extends JsonResource
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
            'tipo' => $this->tipo,
            'data_pedido' => $this->data_pedido->format('d/m/Y'),
            'nome_prestador' => Str::title($this->nome_prestador),
            'prescricao' => $this->prescricao,
            
        ];
    }
}
