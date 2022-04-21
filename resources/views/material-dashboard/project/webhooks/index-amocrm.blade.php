{{--Форма для добавления вебхука--}}
<div class="row justify-content-center">
    <div class="col-3">
        <div class="card my-3">
            <div class="card-body">
                <p class="card-text text-center my-0 py-0">
                    <a href="{{route('webhook.create', ['project' => $project, 'form' => 'extended_amocrm_create'])}}" class="btn btn-primary">
                        @lang('projects.notifications.webhooks.add')
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

{{--Список доступных вебхуков--}}
@include('material-dashboard.project.webhooks._list-new', ['type' => 'amocrm', 'form' => 'extended_'])
