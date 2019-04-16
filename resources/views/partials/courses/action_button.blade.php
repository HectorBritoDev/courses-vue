<div class="col-2">



    {{-- Esta el usuario autenticado? --}}
    @auth

    {{-- Es profesor? --}}
    @can('opt_for_course', $course)

    {{-- se puede suscribir? si se puede suscribir (a la plataforma) es que no tiene ningún plan contratado --}}
    @can('subscribe', \App\Course::class)

    {{-- Boton para ir a la pagina de planes y pagos --}}
    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
        <i class="fa fa-bolt"> {{ __('Suscribirme') }}</i>
    </a>
    @else

    <!-- El usuario ya tiene algun plan y tenemos que saber si se puede inscribir en el curso
         osea, si ya esta inscrito o no en el curso)-->
    @can('inscribe', $course)
    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
        <i class="fa fa-bolt"> {{ __('Inscribirme') }}</i>
    </a>

    @else
    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
        <i class="fa fa-bolt"> {{ __('Inscrito') }}</i>
    </a>

    @endcan

    @endcan

    @else
    {{-- El usuario es profesor o autor del curso --}}
    <a class="btn btn-subscribe btn-bottom btn-block" href="#">
        <i class="fa fa-user"> {{ __('Soy autor/profesor') }}</i>
    </a>
    @endcan

    @else
    {{-- No se ha logeado --}}
    <a class="btn btn-subscribe btn-bottom btn-block" href="{{ route('login') }}">
        <i class="fa fa-user"> {{ __('Acceder') }}</i>
    </a>
    @endauth
</div>
