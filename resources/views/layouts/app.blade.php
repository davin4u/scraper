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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
<div class="container">
    <div id="app">
        @if(\Illuminate\Support\Facades\Auth::user())
        @include('layouts.menu')
        @endif


        <main class="py-4 d-md-flex">
            @if(\Illuminate\Support\Facades\Auth::user())
            @include('layouts.menu_sidebar')
            @endif
            @yield('content')
        </main>

    </div>
</div>

@yield('scripts')
</body>
</html>
