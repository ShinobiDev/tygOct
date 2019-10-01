<?php

namespace App\Listeners;

use App\Events\FacturaCreada;
use App\Mail\DatosFacturaCreada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarDatosFacturaCreada
{
    
    /**
     * Handle the event.
     *
     * @param  FacturaCreada  $event
     * @return void
     */
    public function handle(FacturaCreada $event)
    {
        Mail::to($event->user)->queue(
            new DatosFacturaCreada($event->user, $event->orden)
        );
    }
}
