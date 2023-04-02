<?php

namespace App\Http\Resources;

use App\Comercial;
use App\Marca;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicamentoProdutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'preco' => $this->preco,
            'descricao_completa' => $this->descricao_completa,
            'preco_promocao' => $this->preco_promocao,
            'tarja' => $this->tarja,
            'marca' => $this->getMarcas(),
            'comercial' => $this->getComercial(),
            'promocao' => $this->promocao
        ];
    }

    private function getMarcas()
    {
        $marca = Marca::where('id', $this->marca_id)->first()->nome;
        return $marca;
    }

    private function getComercial()
    {
        $comercial = Comercial::where('id', $this->comercial_id)->first()->nome_fantasia;
        return $comercial;
    }
}
