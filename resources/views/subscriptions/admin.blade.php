@extends('layouts.app')



@section('jumbotron')

@include('partials.jumbotron',['title'=> __('Manejar mis suscripciones'),'icon'=>'list-ol'])
@endsection

@section('content')

<div class="pl-5 pr-5">
    <div class="row justify-content-around">
        <table class="table table-hover table-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Plan</th>
                    <th>ID Suscripción</th>
                    <th>Cantidad</th>
                    <th>Alta</th>
                    <th>Finaliza en</th>
                    <th>Cancelar / Reanudar</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->id }}</td>
                    <td>{{ $subscription->name }}</td>
                    <td>{{ $subscription->stripe_plan }}</td>
                    <td>{{ $subscription->stripe_id }}</td>
                    <td>{{ $subscription->quantity }}</td>
                    <td>{{ $subscription->created_at->format('d/m/y') }}</td>
                    <td>
                        {{ $subscription->ends_at ? $subscription->ends_at->format('d/m/y'): __('Suscripción activa') }}
                    </td>
                    <td>
                        @if ($subscription->ends_at)

                        <form action="{{ route('subscriptions.resume') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $subscription->name }}">
                            <button class="btn btn-success">{{ __('Reanudar') }}</button>
                        </form>

                        @else
                        <form action="{{ route('subscriptions.cancel') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $subscription->name }}">
                            <button class="btn btn-danger">{{ __('Cancelar') }}</button>
                        </form>

                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8">{{ __('No hay ninguna suscripción disponible') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@endsection
