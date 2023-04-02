<?php

namespace App\Http\Resources;

use App\Instituicao;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstituicaoCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (Instituicao $instituicao) {
                return new InstituicaoResource($instituicao);
            }),
        ];
    }
}
