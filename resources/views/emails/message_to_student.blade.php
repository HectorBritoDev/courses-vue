@component('mail::message')

{{-- TODO DEBE ESTAR SIN TABULACIONES O CON POCAS YA QUE PUEDE TENER PROBLEMAS AL RENDERIZAR LA PLANTILLA --}}
# {{ __('Nuevo Mensaje') }}

{{ $text_message }}

@component('mail::button',['url'=>url('/')])
    {{ __('Ir a :app',['app'=> env('APP_NAME')]) }}
@endcomponent

{{ __('Gracias') }}, <br>
{{ config('app.name') }}

@endcomponent
