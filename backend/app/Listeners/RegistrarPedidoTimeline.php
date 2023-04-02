<?php

namespace App\Listeners;

use App\Events\PedidoTimeline as EventTimeline;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\PedidoTimeline;
use Illuminate\Support\Facades\Log;

class RegistrarPedidoTimeline
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  PedidoTimeline  $event
     * @return void
     */
    public function handle(EventTimeline $event)
    {
        $dados = [
            'pedidos_id' => $event->pedido->id,
            'descricao' => $event->descricao,
            'data_mudanca' => $event->data_mudanca,
            'mudancas' => $event->mudanca
        ];

        if($event->usuario){
            $dados['usuario_id']= $event->usuario->id;
            $dados['usuario_type']= get_class($event->usuario);
        }

        $timeline = PedidoTimeline::create($dados);
    }
}
