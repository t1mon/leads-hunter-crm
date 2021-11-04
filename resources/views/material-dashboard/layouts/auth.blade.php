<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="api-token" content="{{ auth()->user()->api_token }}">
    @endauth

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/material-dashboard-app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-200">
<div id="app">
    @yield('content')
</div>
<!-- Scripts -->
<script src="{{ mix('/js/material-dashboard-app.js') }}"></script>
<script src="{{ asset('/js/material-dashboard.js') }}"></script>




</body>
</html>
