@extends('material-dashboard.layouts.app')

@section('content')

{{--Вкладки--}}
<div>
    <ul class="nav nav-tabs">
        {{--Информационная панель--}}
        <li class="nav-item">
            <a  class="nav-link active" data-bs-toggle="tab" href="#info">
                @lang('projects.notifications.tab_info')
            </a>
        </li>

        {{--Настройки хостов--}}
        <li class="nav-item">
            <a  class="nav-link" data-bs-toggle="tab" href="#email">
                @lang('projects.notifications.email_notification')
            </a>
        </li>

        {{--Настройки разрешений пользователей--}}
        <li class="nav-item">
            <a  class="nav-link" data-bs-toggle="tab" href="#telegram">
                @lang('projects.notifications.telegram_notification')
            </a>
        </li>

        {{--Настройки вебхуков--}}
        <li class="nav-item">
            <a  class="nav-link" data-bs-toggle="tab" href="#webhooks">
                @lang('projects.notifications.tab_webhooks')
            </a>
        </li>

    </ul>
</div>

{{--Содержимое вкладок--}}
<div class="tab-content">
    {{--Информационная панель--}}
    <div class="tab-pane fade show active" id="info" role="tabpanel">
        @include('material-dashboard.project._info')
    </div>

    {{--Настройки e-mail--}}
    <div class="tab-pane fade show" id="email" role="tabpanel">
        @include('material-dashboard.project._emails')
    </div>

    
    {{--Настройки Telegram--}}
    <div class="tab-pane fade" id="telegram" role="tabpanel">
        @include('material-dashboard.project._telegram')
    </div>

    {{--Настройки вебхуков--}}
    <div class="tab-pane fade" id="webhooks" role="tabpanel">
        @include('material-dashboard.project.webhooks.index')
    </div>

</div>

@endsection