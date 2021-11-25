<div class="card align-self-center my-3">
    <div class="card-body">
        <div class="text-center">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#add-form" aria-expanded="false" aria-controls="add-form">
                @lang('projects.notifications.webhooks.add')
            </button>
        </div>
        
        <div class="collapse" id="add-form">
            {!! Form::open(['route' => ['webhook.store', $project] ]) !!}
            <div class="border rounded-3 align-middle my-3 p-3">
                <div class="border-bottom my-2">
                    {!! Form::hidden('enabled', true) !!}
                    {!! Form::hidden('type', $type) !!}
                    {!! Form::text(
                        'name',
                        null,
                        [
                            'class' => 'form-control',
                            'placeholder' => trans('projects.notifications.webhooks.name'),
                        ]) !!}
                </div>

                <div class="my-2">
                    {!! Form::text(
                        'url',
                        null,
                        [
                            'class' => 'form-control',
                            'placeholder' => trans('projects.notifications.webhooks.placeholder'),
                        ]) !!}
                </div>
            </div>

            <div class="border rounded-3 form-check my-3 p-3 align-middle">
                <span class="fw-bold me-3">@lang('projects.notifications.webhooks.method'):</span>
                {!! Form::radio('method', 'POST', true, ['class' => 'form-check-input', 'id' => 'method-post']) !!}
                {!! Form::label('method-post', 'POST', ['class' => 'form-check-label me-1']) !!}
                {!! Form::radio('method', 'GET', false, ['class' => 'form-check-input', 'id' => 'method-get']) !!}
                {!! Form::label('method-get', 'GET', ['class' => 'form-check-label']) !!}
            </div>

            <div class="border rounded-3 container my-3 p-3 align-middle">
                <h6 class="fw-bold text-secondary text-center">@lang('projects.notifications.webhooks.fields')</h6>
                <div class="row row-cols-3">
                    @foreach($fields as $field)
                        <div class="col my-1 mx-3">
                            {!! Form::checkbox('fields[]', $field, false, ['id' => $field . '_checkbox' ]) !!}
                            {!! Form::label( $field . '_checkbox', trans("projects.notifications.webhooks.$type.fields.$field") ) !!}                            
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                {!! Form::submit(trans('projects.button-add'), ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>        
    </div>
</div>