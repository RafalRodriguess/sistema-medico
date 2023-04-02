<?php

namespace App\Http\Resources;

use App\GruposProcedimentos;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GruposProcedimentosCollection extends ResourceCollection
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
            'data' => $this->collection->map(function(GruposProcedimentos $grupo) {
                return new GruposProcedimentosResource($grupo);
            })
        ];
    }
}
