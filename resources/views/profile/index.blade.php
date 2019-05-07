@extends('layouts.app')

@section('jumbotron')
@include('partials.jumbotron',['title'=>__('Configurar tu perfil'),'icon'=>'user-circle'])
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}">
@endpush

@section('content')
<div class="pl-5 pr-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Actualiza tus datos') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">
                                {{ __('Correo Electrónico') }}
                            </label>
                            <div class="col-md-6">
                                <input type="email" name="email" id="email" value="{{ $user->email }}"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid':''}}" readonly>

                                @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">
                                {{ __('Contraseña') }}
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="password" id="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid':''}}">

                                @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                {{ __('Confirma la contraseña') }}
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="password_confirmation" id="password-confirm"
                                    class="form-control{{ $errors->has('password-confirm') ? ' is-invalid':''}}">

                                @if ($errors->has('password-confirm'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password-confirm') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-0 row">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Actualizar datos') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if ( !$user->teacher)
            <div class="card">
                <div class="card-header">
                    {{ __('Convertirme en profesor de la plataforma') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('solicitude.teacher') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-block">
                            <i class="fa fa-graduation-cap"></i> {{ __('Solicitar') }}
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-header">
                    {{ __('Administrar los cursos que imparto') }}
                </div>
                <div class="card-body">
                    <a href="{{ route('teacher.courses') }}" class="btn btn-secondary btn-block">
                        <i class="fab fa-leanpub"></i> {{ __('Administrar Ahora') }}
                    </a>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    {{ __('Mis estudiantes') }}
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" id="students-table">
                        <thead>
                            <tr>
                                <td>{{ __('ID') }}</td>
                                <td>{{ __('Nombre') }}</td>
                                <td>{{ __('Email') }}</td>
                                <td>{{ __('Cursos') }}</td>
                                <td>{{ __('Acciones') }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            @endif

            @if ($user->socialAccount)
                <div class="card">
                    <div class="card-header">
                        {{ __('Acceso con Socialite') }}
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-dark btn-block">
                            {{ __('Registrado con') }}: <i class="fa fa-{{ $user->socialAccount->provider }}"></i>
                            {{ $user->socialAccount->provider  }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('partials.modal')
@endsection

@push('scripts')
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script>
    let dt;
    let modal = jQuery('#exampleModal');
    jQuery(document).ready(function () {
        dt = jQuery('#students-table').DataTable({
            pageLength: 5,
            lengthMenu : [5,10,25,50,75,100],
            processing: true,
            serverSide: true,
            ajax: '{{ route("teacher.students") }}',
            languaje: {
                url:"//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            columns:[
                {data:'user.id',visible:false},
                {data:'user.name'},
                {data:'user.email'},
                {data:'courses_formatted'},
                {data:'actions'},
            ]
            //FINAL DATATABLE
        });

        jQuery(document).on('click','.btnEmail',function(e){
            e.preventDefault();
            const id = jQuery(this).data('id');
            modal.find('.modal-title').text('{{ __("Enviar mensaje") }}');
            modal.find('#modalAction').text('{{ __("Enviar mensaje") }}').show();

            let $form = $("<form id='studentsMessage'></form>");

            $form.append(`<input type="hidden" name="user_id" value="${id}" />`) //Uso de comillas inversas ``
            $form.append(`<textarea class="form-control" name="message"></textarea>`)
            modal.find('.modal-body').html($form);
            modal.modal();

        //FINAL CLICK BTNEMAIL
        });

        jQuery(document).on('click','#modalAction',function(e){

            jQuery.ajax({

                url:'{{ route("teacher.send_message_to_student") }}',
                type:'POST',
                headers:{
                    'x-csrf-token': $("meta[name=csrf-token]").attr('content')
                },
                data:{
                    info: $('#studentsMessage').serialize()
                },
                success:(response)=>{
                    if (response.data) {
                        modal.find('#modalAction').hide();
                        modal.find('.modal-body').html('<div class="alert alert-success">{{ __("Mensaje enviado correctamente") }}</div>');

                    }else{
                        modal.find('.modal-body').html('<div class="alert alert-danger">{{ __("Ha ocurrido un error enviando el mensaje") }}</div>');

                    }
                }
                //FINAL AJAX
            });

            //FINAL CLICK modalACTION
        });
        //FINAL DOCUMENT READY
    });

</script>
@endpush
