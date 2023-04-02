<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class PedidoTimeline
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pedido;
    public $descricao;
    public $data_mudanca;
    public $mudanca;
    public $usuario;
    public $usuario_tipo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($pedido, $descricao, $usuario = null,$mudanca)
    {
        $this->pedido = $pedido;
        $this->descricao = $descricao;
        $this->data_mudanca = Carbon::now();
        $this->usuario = $usuario;
        $this->mudanca = $mudanca;
        // $this->usuario_tipo = $usuario_tipo;
    }



    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
