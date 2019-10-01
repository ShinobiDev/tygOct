<?php

namespace App\Listeners;

use App\Events\UsuarioCreado;
use App\Mail\EnviarCredencialesLogin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CredencialesLogin
{
    

    /**
     * Handle the event.
     *
     * @param  UsuarioCreado  $event
     * @return void
     */
    public function handle(UsuarioCreado $event)
    {
        //dd($event->user);
        Mail::to($event->user)->queue(
            new EnviarCredencialesLogin($event->user, $event->pass)
        );
    }
}
