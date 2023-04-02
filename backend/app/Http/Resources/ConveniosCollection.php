<?php

namespace App\Http\Resources;

use App\Convenio;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConveniosCollection extends ResourceCollection
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
            'data' => $this->collection->map(function(Convenio $convenios) {
                return new ConvenioResource($convenios);
            })
        ];
    }
}
