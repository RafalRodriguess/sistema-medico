<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificacaoFCM
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notificacao;
    public $data;
    public $usuario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($titulo, $corpo, $url, $usuario )
    {
        $this->notificacao = [
            'title'=>$titulo,
            'body'=>$corpo,
            'imageUrl'=>asset('material/assets/images/logo-h.png')
        ];

        $this->usuario = $usuario;
        $this->data = ['url' => $url];
    }

    public function obterNotificacao()
    {
        return $this->notificacao;
    }

    public function obterData()
    {
        return $this->data;
    }

    public function obterUsuario()
    {
        return $this->usuario;
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
