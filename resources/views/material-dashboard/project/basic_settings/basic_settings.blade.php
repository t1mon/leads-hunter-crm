@extends('material-dashboard.layouts.app')

@section('content')

<div class="container-sm">
    <div class="row justify-content-center">
        {{-- Кнопка включения-выключения проекта --}}
        @include('material-dashboard.project.basic_settings.project-toggle')
    </div>
</div>

@endsection