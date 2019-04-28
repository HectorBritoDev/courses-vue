@extends('layouts.app')

@section('jumbotron')
@include('partials.jumbotron',['title'=>__('Cursos a los que estas suscrito'),'icon'=>'table'])
@endsection

@section('content')
<div class="pl-5 pr-5">
    <div class="justify-content-center row">
        @forelse ($courses as $course)
        <div class="col-md-3">
            @include('partials.courses.card_course')
        </div>
        @empty
        <div class="alert alert-dark">{{ __('Todavía no estás suscrito a ningún curso') }}</div>
        @endforelse
    </div>
</div>
@endsection
