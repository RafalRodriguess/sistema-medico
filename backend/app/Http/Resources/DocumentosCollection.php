<?php

namespace App\Http\Resources;

use App\Prontuario;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentosCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (Prontuario $prontuario) {
                return new ProntuariosResource($prontuario);
            }),
        ];
    }
}
