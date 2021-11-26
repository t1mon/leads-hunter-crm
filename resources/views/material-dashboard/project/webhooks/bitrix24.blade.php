{{--Форма для добавления вебхука--}}
@include('material-dashboard.project.webhooks._add-form', ['type' => \App\Models\Project\Project::WEBHOOK_BITRIX24,])

{{--Список доступных вебхуков--}}
@include('material-dashboard.project.webhooks._list', ['type' => \App\Models\Project\Project::WEBHOOK_BITRIX24,])