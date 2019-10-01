<?php

namespace App\Listeners;

use App\Events\OrdenParaFacturar;
use App\Mail\DatosOrdenParaFacturar;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosOrdenParaFacturar
{

    /**
     * Handle the event.
     *
     * @param  OrdenParaFacturar  $event
     * @return void
     */
    public function handle(OrdenParaFacturar $event)
    {
         Mail::to($event->user)->queue(
            new DatosOrdenParaFacturar($event->user, $event->orden)
        );
    }
}
