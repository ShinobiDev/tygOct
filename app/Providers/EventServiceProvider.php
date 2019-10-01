<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UsuarioCreado' => [
            'App\Listeners\CredencialesLogin',
        ],
        'App\Events\OrdenCreada' => [
            'App\Listeners\EnviarDatosOrdenCreada',
        ],
        'App\Events\OrdenAsignada' => [
            'App\Listeners\EnviarDatosOrdenAsignada',
        ],
        'App\Events\OrdenAceptada' => [
            'App\Listeners\EnviarDatosOrdenAceptada',
        ],
        'App\Events\SolicitudNegociacionOrden' => [
            'App\Listeners\EnviarDatosNegociacionOrden',
        ],
        'App\Events\FacturaCreada' => [
            'App\Listeners\EnviarDatosFacturaCreada',
        ],
        'App\Events\OrdenParaFacturar' => [
            'App\Listeners\EnviarDatosOrdenParaFacturar',
        ],
        'App\Events\NotificationEvent' =>[
            'App\Listeners\SendNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
