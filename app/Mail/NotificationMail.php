<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationMail extends Mailable
{

  public $user;
  public $obj;
  public $tipo;



    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$obj,$tipo)
    {
       //dd($this->tipo);
       $this->user = $user;
       $this->obj = $obj;
       $this->tipo = $tipo;
       //dd($this);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($this->tipo);
        switch ($this->tipo) {
            case 'CambioEstadoCotizado':
                //dd($this);
                return $this->markdown('emails.CambioEstadoCliente')
                            ->subject('La orden  N° '.$this->obj[0].' ha sido gestionada '.config('app.name'));
                # code...
                break;
            default:
                dd($this->tipo);
                # code...
                break;
        }

    }
}
