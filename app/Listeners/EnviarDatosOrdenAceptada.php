<?php

namespace App\Listeners;

use App\Events\OrdenAceptada;
use App\Mail\DatosOrdenAceptada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosOrdenAceptada
{

    /**
     * Handle the event.
     *
     * @param  OrdenAceptada  $event
     * @return void
     */
    public function handle(OrdenAceptada $event)
    {
        Mail::to($event->user)->queue(
            new DatosOrdenAceptada($event->user, $event->orden)
        );
    }
}
