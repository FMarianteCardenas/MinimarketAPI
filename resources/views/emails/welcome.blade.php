@component('mail::message')
# Bienvenido a Minimarket {{$user->name}}

Gracias por crear una cuenta, desde aquí podrá administrar su minimercado.
Por Favor, valide su email por medio del siguiente botón:

@component('mail::button', ['url' => route('verify',$user->verification_token)])
Confirmar Correo
@endcomponent

Muchas Gracias,<br>
{{ config('app.name') }}

NOTA:Si Usted no ha creado esta cuenta, ignore este email.
@endcomponent