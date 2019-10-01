@component('mail::message')
# Tus datos de acceso a {{ config('app.name') }}

Utiliza estas credenciales para acceder al sistema.

@component('mail::table')
	| Usuario | Contraseña |
	|:---------|:------------|
	| {{ $user }} | {{ $pass }} |

@endcomponent

@component('mail::button', ['url' => 'login'])
Ingresar
@endcomponent

Gracias,
{{ config('app.name') }}.
@endcomponent
