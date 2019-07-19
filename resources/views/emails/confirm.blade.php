@component('mail::message')
# Hola {{$user->name}}

Haz cambiado tu dirección de correo. Por favor confirme su nueva dirección de correo por medio del siguiente botón:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
Confirmar Correo
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}

NOTA:Si Usted no ha modificado su correo, ignore este email.
@endcomponent