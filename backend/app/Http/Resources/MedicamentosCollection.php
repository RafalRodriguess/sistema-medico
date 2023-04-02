<?php

namespace App\Http\Resources;

use App\Medicamento;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicamentosCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (Medicamento $medicamento) {
                return new MedicamentoResource($medicamento);
            }),
        ];
    }
}
