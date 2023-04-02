<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PedidoMensagensResource extends JsonResource
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
            'pedido_id' => $this->pedido_id,
            'remetente' => $this->remetente,
            'mensagem' => $this->mensagem,
            'data' => $this->created_at ? $this->created_at->format('d/m/Y H:i') : null,
            'data_visto' => $this->data_visto ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data_visto)->format('d/m/Y H:i') : null,
            'visto' => $this->visto,
        ];
    }
}
