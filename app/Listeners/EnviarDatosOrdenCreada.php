<?php

namespace App\Listeners;

use App\Events\OrdenCreada;
use App\Mail\DatosOrdenCreada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosOrdenCreada
{

    /**
     * Handle the event.
     *
     * @param  UsuarioCreado  $event
     * @return void
     */
    public function handle(OrdenCreada $event)
    {
        Mail::to($event->user)->queue(
            new DatosOrdenCreada($event->user, $event->orden)
        );
    }
}
