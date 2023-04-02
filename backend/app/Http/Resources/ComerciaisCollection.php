<?php

namespace App\Http\Resources;

use App\Comercial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @see \App\Comercial
 */
class ComerciaisCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function (Comercial $comercial) {
                return new ComercialResource($comercial);
            }),
        ];
    }
}
