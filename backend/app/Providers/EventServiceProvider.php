<?php

namespace App\Providers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PedidoTimeline;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\RegistrarCliente;
use App\Listeners\RegistrarPedidoTimeline;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            // SendEmailVerificationNotification::class,
            RegistrarCliente::class
        ],
        PedidoTimeline::class => [
            RegistrarPedidoTimeline::class
        ]
    ];

    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen(Logout::class, function (Logout $logout) {
            if ($logout->guard === 'comercial') {
                request()->session()->remove('comercial');
            }
        });

        Event::listen(Logout::class, function (Logout $logout) {
            if ($logout->guard === 'instituicao') {
                request()->session()->remove('instituicao');
            }
        });

    }
}
