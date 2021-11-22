@extends('material-dashboard.layouts.app')

@section('content')

<div>
    {{--Навигационное меню--}}
    <div>
        <ul class="nav nav-tabs">
            {{--Настройки хостов--}}
            <li class="nav-item">
                <a  class="nav-link active" data-bs-toggle="tab" href="#email">
                    @lang('projects.notifications.email_notification')
                </a>
            </li>

            {{--Настройки разрешений пользователей--}}
            <li class="nav-item">
                <a  class="nav-link" data-bs-toggle="tab" href="#telegram">
                    @lang('projects.notifications.telegram_notification')
                </a>
            </li>

        </ul>
    </div>

    <div class="tab-content">
        {{--Настройки e-mail--}}
        <div class="tab-pane fade show active" id="email" role="tabpanel">
            @include('material-dashboard.project._emails')
        </div>

        
        {{--Настройки Telegram--}}
        <div class="tab-pane fade" id="telegram" role="tabpanel">
            @include('material-dashboard.project._telegram')
        </div>

    </div>
</div>

@endsection