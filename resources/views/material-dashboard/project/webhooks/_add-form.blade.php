<div class="card align-self-center my-3">
    <div class="card-body">
        <div class="text-center">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#add-form" aria-expanded="false" aria-controls="add-form">
                @lang('projects.notifications.webhooks.add')
            </button>
        </div>
        
        <div class="collapse" id="add-form">
            @include('material-dashboard.project.webhooks._form', ['type' => $type,])
        </div>        
    </div>
</div>