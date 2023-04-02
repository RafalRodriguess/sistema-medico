<?php

namespace App\Http\Resources;

use App\Procedimento;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListaInstituicaoExamesCollection extends ResourceCollection
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
            'data' => $this->collection->map(function($instituicaoExames) {
                return new ListaInstituicaoExamesResource($instituicaoExames);
            }),
        ];
    }
}
