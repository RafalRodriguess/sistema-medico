<?php

namespace App\Http\Resources;

use App\UsuarioCartao;
use App\UsuarioEndereco;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
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
            'cpf' => $this->cpf,
            'data_nascimento' => $this->data_nascimento->format('d/m/Y'),
            'telefone' => $this->telefone,
            'endereco' => $this->Endereco(),
            'cartao' => $this->Cartao(),
            'convenio_id' => $this->convenio_id,
        ];
    }

    private function Endereco()
    {
        $endereco = UsuarioEndereco::where('usuario_id', $this->id)->get();
        return $endereco;
    }
    
    private function Cartao()
    {
        $cartao = UsuarioCartao::where('usuario_id', $this->id)->get();
        return $cartao;
    }
}
