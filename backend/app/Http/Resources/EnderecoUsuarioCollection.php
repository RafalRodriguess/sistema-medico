<?php

namespace App\Http\Resources;

use App\UsuarioEndereco;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EnderecoUsuarioCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (UsuarioEndereco $endereco) {
                return new UsuarioEnderecoResource($endereco);
            }),
        ];
    }
}
