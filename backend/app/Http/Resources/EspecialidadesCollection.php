<?php

namespace App\Http\Resources;

use App\Especialidade;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EspecialidadesCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (Especialidade $especialidade) {
                return new EspecialidadeResource($especialidade);
            }),
        ];
    }
}
