<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

    <link rel="apple-touch-icon" sizes="180x180" href="/media/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/media/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/media/icons/favicon-16x16.png">
    <link rel="manifest" href="/media/icons/site.webmanifest">
    <link rel="mask-icon" href="/media/icons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/media/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/media/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

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
<body  class="g-sidenav-show g-sidenav-pinned bg-gray-200">
<div id="app">
@include('material-dashboard.shared.navbar-vertical-left')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    @include('material-dashboard.shared.navbar-main-horizontal')
    <div class="container-fluid py-4">

            @yield('content')

    </div>
</main>

    <settings-bar></settings-bar>


</div>

<!-- Scripts -->
<script src="{{ mix('/js/material-dashboard-app.js') }}" async defer></script>

</body>
</html>
