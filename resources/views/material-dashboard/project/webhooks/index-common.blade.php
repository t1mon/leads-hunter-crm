{{--Форма для добавления вебхука--}}
<div class="card my-3">
    <div class="card-body">
        <div class="card-text text-center">
            <p class="my-0 py-0">
                <a href="{{route('webhook.create', ['project' => $project, 'form' => 'common-simple'])}}" class="btn btn-primary">
                    @lang('projects.notifications.webhooks.add')
                </a>
            </p>
            
            <p class="my-0 py-0">
                <a href="{{route('webhook.create', ['project' => $project, 'form' => 'extended'])}}">
                    Расширенная форма
                </a>
            </p>
            
        </div>
        
    </div>
</div>

{{--Список доступных вебхуков--}}
@include('material-dashboard.project.webhooks._list-new', ['type' => 'common'])