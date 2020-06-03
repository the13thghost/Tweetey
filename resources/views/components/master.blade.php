<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
    {{-- <section class="px-4 py-4 mb-6">
        <header class="container mx-auto">
    <img src="{{asset('/images/logo.png')}}" alt="logo" width="140px">
        </header>
    </section> --}}
    {{$slot}}
    </div>
    <script rel="javascript" type="text/javascript" href="{{asset('js/ajax.js')}}"></script>

    {{-- <script src="http://unpkg.com/turbolinks"></script> --}}
    <script src="{{ asset('js/main.js') }}" defer></script>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/ajax.js') }}" defer></script>
</body>
</html>
