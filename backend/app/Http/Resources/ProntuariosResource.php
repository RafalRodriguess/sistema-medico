<?php

namespace App\Http\Resources;
use Illuminate\Support\Str;

use Illuminate\Http\Resources\Json\JsonResource;

class ProntuariosResource extends JsonResource
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
            'data' => $this->data->format('d/m/Y'),
            'tipo_atendimento' => Str::title($this->tipo_atendimento),
            'origem_atendimento' => Str::title($this->origem_atendimento),
            'nome_convenio' => Str::title($this->nome_convenio),
            'procedimento' => Str::title($this->procedimento),
            'instituicao_nome' => $this->instituicao->nome,
            'anamnese_qp' => Str::title($this->anamnese_qp),
            'nome_prestador' => Str::title($this->nome_prestador),
            'especialidade_atendimento' => Str::title($this->especialidade_atendimento),
            'anamnese_cid' => Str::title($this->anamnese_cid),
            'anamnese_descricao_cid' => Str::title($this->anamnese_descricao_cid),
            
        ];
    }

    // private function getMarcas()
    // {
    //     if ($this->marca_id) {
    //         $marca = Marca::where('id', $this->marca_id)->first()->nome;
    //         return $marca;
    //     }

    //     return '';
        
    // }
    
}
