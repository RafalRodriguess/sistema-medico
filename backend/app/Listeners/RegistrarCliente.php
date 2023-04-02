<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Libraries\PagarMe;

use App\Usuario;

class RegistrarCliente
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        if ($user instanceof Usuario){
            $pagarMe = new PagarMe();

            $cliente_pagarme = $pagarMe->cadastrarCliente([
                'name' => $user->nome,
                'email' => 'teste@gmail.com',
                // 'email' => $user->email,
                'external_id' => (string) $user->id,
                'type' => 'individual',
                'country' => 'br',
                'phone_numbers' => [
                    '+55'.preg_replace('/\D/', '', $user->telefone)
                ],
                'documents' => [
                    [
                    'type' => 'cpf',
                    'number' => preg_replace('/\D/', '', $user->cpf)
                    ]
                ]
            ]);

            $user->update(['customer_id' => $cliente_pagarme->id]);
        }




    }
}
