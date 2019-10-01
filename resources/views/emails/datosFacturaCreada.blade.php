@component('mail::message')
# Se ha generado una factura de la Orden N° {{$orden}}.
 
Para ver los detalles de la factura, pulsa el siguiente boton.

@component('mail::button', ['url' => url('login')])
Ir a T&G
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
