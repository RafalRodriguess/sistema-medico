<?php

namespace App\Http\Resources;

use App\Comercial;
use App\Marca;
use App\Medicamento;
use App\ProdutoPergunta;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ComercialProdutoFilterResource extends JsonResource
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
            'categorias' => $this->getCategorias(),
            'subcategorias' => $this->getSubcategorias(),
            'marcas' => $this->getMarcas(),
        ];
    }

    private function getCategorias(){
        if($this->comercial->id){
            $comercial = Comercial::where('id', $this->comercial->id)->first();
            $categorias = $comercial->categorias()->wherehas('produto')->select(['id','nome'])->get();
            return $categorias;
        }else{
            return '';
        }

    }

    private function getSubcategorias(){
        if($this->comercial->id){
            $comercial = Comercial::where('id', $this->comercial->id)->first();
            $subcategorias = $comercial->subCategorias()->wherehas('produto')->select(['id','nome'])->get();
            return $subcategorias;
        }else{
            return '';
        }
    }

    private function getMarcas(){
        if($this->comercial->id){
            $marcas = Marca::whereHas('produtos',function($q){
                $q->where('comercial_id',$this->comercial->id);
            })->select(['id','nome'])->get();
            return $marcas;
        }else{
            return '';
        }
    }

}
