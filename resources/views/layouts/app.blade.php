<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        window.App ={!! json_encode ([
        'signedIn'=>auth()->check(),
        'user'=>auth()->user(),
        ]) !!};
    </script>
    <style>
        body {
            padding-bottom: 100px
        }

        .level {
            display: flex;
            align-items: center;
        }

        .flex {
            flex: 1;
        }
        .ml-a{
            margin-left: auto;
        }
        .card-success{
            background-color: #d6e9c6;
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        [v-cloak] {
            display: none;
        }
    </style>
    @yield('header')
</head>
<body>
<div id="app">
    @include('layouts.nav')
    <main class="py-4">
        @yield('content')
    </main>

    <flash message="{{session('flash')}}"></flash>

</div>
@yield('scripts')
</body>
</html>
