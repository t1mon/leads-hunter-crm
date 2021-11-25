{{--Поля--}}
@php
    $fields = ['name', 'surname', 'patronymic', 'phone', 'entries', 'email', 'cost', 'comment', 'city', 'host', 'ip', 'referrer', 'url_query_string'];
@endphp

{{--Форма для добавления вебхука--}}
@include('material-dashboard.project.webhooks._form', ['type' => \App\Models\Project\Project::WEBHOOK_COMMON,])

{{--Список доступных вебхуков--}}
@include('material-dashboard.project.webhooks._list', ['type' => \App\Models\Project\Project::WEBHOOK_COMMON,])