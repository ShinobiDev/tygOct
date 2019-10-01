@component('mail::message')
{{ config('app.name') }}
# Tu oden N° {{$obj[0]}}

Para ver más información de la orden, pulsa el siguiente boton.

@component('mail::button', ['url' => url('login')])
Ir a T&G
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent