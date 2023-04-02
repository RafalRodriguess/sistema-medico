<?php

namespace App\Listeners;

use App\Events\NotificacaoFCM;
use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Usuario;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class EnviarNotificacao
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NotificacaoFCM $event
     * @return void
     */
    public function handle(NotificacaoFCM $event)
    {
        $notificacao = $event->obterNotificacao();
        $data = $event->obterData();
        $usuario = $event->obterUsuario();
        $usuario = usuario::find($usuario);
        $tokens= $usuario->tokens()->whereNotNull('fcm_token')->select('fcm_token')->distinct()->get()->pluck('fcm_token')->toArray();
        if($tokens){
            $messaging = app('firebase.messaging');
            $message = CloudMessage::new()
            ->withNotification($notificacao)
            ->withData($data);

            $messaging->sendMulticast($message, $tokens);
        }


    }
}
