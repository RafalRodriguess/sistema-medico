<?php

namespace App\Http\Resources;

use App\UsuarioCartao;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartaoUsuarioCollection extends ResourceCollection
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
            'data' => $this->collection->map( function (UsuarioCartao $cartao) {
                return  new CartaoUsuarioResource($cartao);
            })
        ];
    }
}
