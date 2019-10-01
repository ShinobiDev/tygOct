<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Support\Facades\Mail;
Use App\Mail\NotificationMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotification
{

    /**
     * Handle the event.
     *
     * @param  NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {

        //dd($url);
        //dd($event);
        Mail::to($event->user->email)->queue(

          new NotificationMail($event->user, $event->obj,$event->tipo)
        );



    }
}
