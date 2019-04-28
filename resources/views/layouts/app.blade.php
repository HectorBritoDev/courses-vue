<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>


        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        @stack('styles')
    </head>

    <body>
        @include('partials.navigation')
        @yield('jumbotron')
        <div id="app">

            <main class="py-4">
                @if (session('message'))
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="alert alert-{{ session('message')[0] }}">
                            <h4 class="alert-heading">{{ __("Mensaje informativo") }}</h4>
                            <p>{{ session('message')[1] }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @yield('content')
            </main>
        </div>
 <!-- Scripts -->
 <script src="{{ asset('js/app.js') }}" ></script>
 @stack('scripts')
    </body>

</html>
