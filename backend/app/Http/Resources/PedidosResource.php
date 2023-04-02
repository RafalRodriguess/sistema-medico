<?php

namespace App\Http\Resources;

use App\Comercial;
use App\PedidoProduto;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidosResource extends JsonResource
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
            'comercial' => $this->comercial(),
            'valor_total' => $this->valor_total,
            'forma_entrega' => $this->forma_entrega,
            'data_entrega' => $this->data_entrega ? $this->data_entrega->format('d/m/Y') : null,
            'status_pedido' => $this->status_pedido,
            'observacao' => $this->observacao,
            'prazo_tipo_minimo' => $this->prazo_tipo_minimo,
            'prazo_tipo' => $this->prazo_tipo,
            'prazo_tipo_maximo' => $this->prazo_tipo_maximo,
            'prazo_maximo' => $this->prazo_maximo,
            'prazo_minimo' => $this->prazo_minimo,
            'produtosPedido' => $this->produtos(),
            'entregue' => $this->verificaEntrega(),
            'entregue_comercial' => $this->entrega_comercial,
            'forma_pagamento' => $this->forma_pagamento
        ];
    }

    private function comercial()
    {
        $comercial = Comercial::where('id', $this->comercial_id)->first();

        return new ComercialResource($comercial);
    }

    private function produtos()
    {
        $produtoPedido = PedidoProduto::where('pedido_id', $this->id)->get();

        return $produtoPedido;
    }

    private function verificaEntrega()
    {
        if($this->entrega_sistema == null && $this->entrega_cliente == null){
            return true;
        }

        return false;
    }
}
