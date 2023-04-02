<?php

namespace App\Http\Resources;

use App\PedidoMensagem;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PedidoMensagensCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (PedidoMensagem $mensagem) {
                return new PedidoMensagensResource($mensagem);
            }),
        ];
    }
}
