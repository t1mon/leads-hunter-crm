@extends('material-dashboard.layouts.app')

@section('content')

<div>
    Навигационное меню
    <div>
        <ul class="nav nav-tabs">
            Настройки хостов
            <li class="nav-item">
                <a  class="nav-link active" data-bs-toggle="tab" href="#hosts">
                    @lang('projects.properties.tab')
                </a>
            </li>

            Настройки разрешений пользователей
            <li class="nav-item">
                <a  class="nav-link" data-bs-toggle="tab" href="#users">
                    @lang('projects.sidebar.users')
                </a>
            </li>

            Классы проекта
            <li class="nav-item">
                <a  class="nav-link" data-bs-toggle="tab" href="#classes">
                    @lang('projects.classes.tab')
                </a>
            </li>

        </ul>
    </div>

    <div class="tab-content">
        Настройки хостов
        <div class="tab-pane fade show active" id="hosts" role="tabpanel">
            @include('material-dashboard.project._properties')
            @include('material-dashboard.project._hosts')
        </div>

        Настройки разрешений пользователей
        <div class="tab-pane fade" id="users" role="tabpanel">
            @include('material-dashboard.project._users')
        </div>

        Классы проекта
        <div class="tab-pane fade" id="classes" role="tabpanel">
            @include('material-dashboard.project._classes')
        </div>

    </div>
</div>

{{--    <settings-basic></settings-basic>--}}

@endsection
