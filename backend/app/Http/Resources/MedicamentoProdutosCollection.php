<?php

namespace App\Http\Resources;

use App\Produto;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicamentoProdutosCollection extends ResourceCollection
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
            'data' => $this->collection->map(function (Produto $produto) {
                return new MedicamentoProdutoResource($produto);
            }),
        ];
    }
}
