<?php

namespace App\Listeners;

use App\Events\OrdenAsignada;
use App\Mail\DatosOrdenAsignada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosOrdenAsignada
{

    /**
     * Handle the event.
     *
     * @param  OrdenAsignada  $event
     * @return void
     */
    public function handle(OrdenAsignada $event)
    {
        Mail::to($event->user)->queue(
            new DatosOrdenAsignada($event->user, $event->orden)
        );
    }
}
