<?php

namespace App\Listeners;

use App\Events\SolicitudNegociacionOrden;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosNegociacionOrden
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
     * @param  SolicitudNegociacionOrden  $event
     * @return void
     */
    public function handle(SolicitudNegociacionOrden $event)
    {
        //
    }
}
