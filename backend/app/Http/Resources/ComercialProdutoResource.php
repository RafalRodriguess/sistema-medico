<?php

namespace App\Http\Resources;

use App\Categoria;
use App\Comercial;
use App\Marca;
use App\Medicamento;
use App\ProdutoPergunta;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ComercialProdutoResource extends JsonResource
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
            'breve_descricao' => $this->breve_descricao,
            'preco_promocao' => $this->preco_promocao,
            'categoria' => $this->getCategoria(),
            'tarja' => $this->tarja,
            'marca' => $this->getMarcas(),
            'comercial' => $this->getComercial(),
            'promocao' => $this->promocao,
            'quantidade' => $this->quantidade,
            'estoque_ilimitado' => $this->estoque_ilimitado,
            'permitir_comprar_muitos' => $this->permitir_comprar_muitos,
            'promocao_inicio' => ($this->promocao_inicio) ? $this->promocao_inicio->format('d/m/Y') : null,
            'promocao_final' => ($this->promocao_final) ? $this->promocao_final->format('d/m/Y') : null,
            'perguntas' => $this->getPerguntas(),
            'imagens' => [
                'original' => asset(Storage::cloud()->url($this->imagem)),
                'imagem_100px' => asset(Storage::cloud()->url($this->imagem_100px)),
                'imagem_200px' => asset(Storage::cloud()->url($this->imagem_200px)),
                'imagem_300px' => asset(Storage::cloud()->url($this->imagem_300px))
            ],


            // 'medicamentos' => $this->getMedicamento(),
        ];
    }

    private function getMarcas()
    {
        if ($this->marca_id) {
            $marca = Marca::where('id', $this->marca_id)->first()->nome;
            return $marca;
        }

        return '';

    }

    private function getPerguntas()
    {

        $perguntas = ProdutoPergunta::where('produto_id', $this->id)->get();

        if($perguntas){
            foreach ($perguntas as $key => $value) {
                if ($perguntas[$key]->tipo != 'Texto') {
                    $perguntas[$key]['alternativas'] = $perguntas[$key]->produto_pergunta_alternativas()->get();
                }
            }
            return $perguntas;
        }

        return '';

    }

    private function getComercial()
    {
        $comercial = Comercial::where('id', $this->comercial_id)->first(); 
        return new ComercialResource($comercial);
    }

    private function getCategoria()
    {
        $categoria = Categoria::where('id', $this->categoria_id)->first(); 
        return $categoria->nome;
    }

    // private function getMedicamento()
    // {
    //     $medicamentos = [];
    //     $medicamentos = Medicamento::produtos()->where('produto_id', $this->id)->get();
    //     if ($medicamentos) {
    //         return $medicamentos;
    //     }
    //     return false;
    // }

}
