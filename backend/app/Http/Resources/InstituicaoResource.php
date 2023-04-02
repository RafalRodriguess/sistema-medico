<?php

namespace App\Http\Resources;

use App\HorarioFuncionamentoInstituicao;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class InstituicaoResource extends JsonResource
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
            'endereco' => [
                'cep' => $this->cep,
                'logradouro' => $this->rua,
                'numero' => $this->numero,
                'bairro' => $this->bairro,
                'cidade' => $this->cidade,
                'estado' => $this->estado,
                'complemento' => $this->complemento,
                'referencia' => $this->referencia,
            ],
            'imagens' => [
                'imagem' => asset(Storage::cloud()->url($this->imagem)),
                'imagem_100px' => asset(Storage::cloud()->url($this->imagem_100px)),
                'imagem_200px' => asset(Storage::cloud()->url($this->imagem_200px)),
                'imagem_300px' => asset(Storage::cloud()->url($this->imagem_300px))
            ],
            'numero_parcelas' => $this->max_parcela,
            'parcela_gratis' => $this->free_parcela,
            'percento_parcela' => $this->valor_parcela,
            'valor_minimo' => $this->valor_minimo,
            'cartao_entrega' => $this->cartao_entrega,
            'dinheiro' => $this->dinheiro,
            'horario_funcionamento' => $this->getHorarioFuncionamento(),
        ];
    }

    private function getHorarioFuncionamento()
    {
        $horarioFuncionamento = HorarioFuncionamentoInstituicao::where('instituicao_id', $this->id)->get();

        return $horarioFuncionamento;
    }
}
