<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DatosOrdenAsignada extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public  $orden;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $ordenAsignada)
    {   
        $this->user = $user;
        $this->orden =  $ordenAsignada;
        //dd($ordenAsignada);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.Orden_Asignada');
    }
}
