<?php

namespace App\Http\Resources;

use App\Pedido;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PedidosCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (Pedido $pedido) {
                return new PedidosResource($pedido);
            }),
        ];
    }
}
