<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="api-token" content="{{ auth()->user()->api_token }}">
    @endauth

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin.css') }}" rel="stylesheet">
</head>
<body class="admin-body bg-dark">
@include('project/shared/navbar')
<div id="app">
    <div class="content-wrapper bg-dark">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    @include('shared/alerts')

                    <div class="card  bg-dark">
                        <div class="card-body  bg-dark">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{ mix('/js/admin.js') }}"></script>
@stack('inline-scripts')
</body>
</html>
