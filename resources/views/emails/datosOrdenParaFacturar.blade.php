@component('mail::message')
# La Orden N° {{$orden}}  esta lista para facturar.

Para ver los detalles de la factura, pulsa el siguiente boton.

@component('mail::button', ['url' => url('login')])
Ir a T&G
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
