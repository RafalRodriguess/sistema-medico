<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartaoUsuarioResource extends JsonResource
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
            'id_pagarme' => $this->id_pagarme,
            'ultimos_digitos' => $this->ultimos_digitos,
            'bandeira' => $this->bandeira,
            'nome' => $this->nome
        ];
    }
}
