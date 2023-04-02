<?php

namespace App\Http\Resources;

use App\Usuario;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UsuarioCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (Usuario $usuario) {
                return new UsuarioResource($usuario);
            }),
        ];
    }
}
