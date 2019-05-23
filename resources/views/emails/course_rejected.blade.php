@component('mail::message')
#{{ __('Curso rechazado !') }}
{{ __('Tu curso :course no ha sido aprobado en la plataforma', ['course' => $course->name]) }}
<img src="{{ url('storage/courses/'.$course->picture) }}" alt="{{ $course->name }}">

@component('mail::button', ['url'=> url('/courses/'. $course->slug)])
{{ __('Ir a la plataforma') }}
@endcomponent

{{ __('Gracias') }},<br>
{{ config('app.name') }}
@endcomponent
