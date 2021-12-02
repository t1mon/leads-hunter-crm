{{--Поля--}}
@php
        $inputs = ['TITLE', 'STATUS_ID', 'SOURCE_ID', 'SOURCE_DESCRIPTION',];
        $fields = ['DATE_CREATE', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'PHONE', 'EMAIL', 'ADDRESS_CITY', 'OPPORTUNITY', 'COMMENTS',];
@endphp


{!! Form::open(['route' => isset($webhook) ? ['webhook.update', $project, $webhook->name] : ['webhook.store', $project], 'method' => isset($webhook) ? 'PUT' : 'CREATE']) !!}
<div class="border rounded-3 align-middle my-3 p-3">
    <div class="border-bottom my-2">
        {!! Form::hidden('enabled', true) !!}
        {!! Form::hidden('type', \App\Models\Project\Project::WEBHOOK_BITRIX24) !!}
        {!! Form::text(
            'name',
            $webhook->name ?? null,
            [
                'class' => 'form-control',
                'placeholder' => trans('projects.notifications.webhooks.name'),
            ]) !!}
    </div>

    <div class="my-2">
        {!! Form::text(
            'url',
            $webhook->url ?? null,
            [
                'class' => 'form-control',
                'placeholder' => trans('projects.notifications.webhooks.placeholder'),
            ]) !!}
    </div>
</div>

<div class="border rounded-3 form-check my-3 p-3">
    @foreach ($inputs as $input)
        <div class="mb-3">
            {!! Form::label("{$input}_input", trans("projects.notifications.webhooks.bitrix24.fields.$input") . " ({$input}):", ['class' => 'form-label']) !!}
            {!! Form::text("params[$input]", $webhook->params->$input ?? null, ['class' => 'form-control border p-2', 'id' => "{$input}_input", 'placeholder' => trans("projects.notifications.webhooks.bitrix24.fields.$input")]) !!}
        </div>
    @endforeach
    <div class="form-check mb-3">
        <span class="fw-bold me-3"> @lang('projects.notifications.webhooks.bitrix24.fields.OPENED') (OPENED):</span>
        
        {!! Form::radio('params[OPENED]', 'Y', isset($webhook) ? ($webhook->params->OPENED === 'Y' ? true : false) : true, ['id' => 'OPENED_Y_checkbox', 'class' => 'form-check-input']) !!}
        {!! Form::label('OPENED_Y_checkbox', trans('projects.yes'), ['class' => 'form-check-label']) !!}

        {!! Form::radio('params[OPENED]', 'N', isset($webhook) ? ($webhook->params->OPENED === 'N' ? true : false) : false, ['id' => 'OPENED_Y_checkbox', 'class' => 'form-check-input']) !!}
        {!! Form::label('OPENED_Y_checkbox', trans('projects.no'), ['class' => 'form-check-label']) !!}
    </div>
</div>

<div class="border rounded-3 form-check my-3 p-3">
    <span class="fw-bold me-3">@lang('projects.notifications.webhooks.method'):</span>

    {!! Form::radio('method', 'POST', isset($webhook) ? ($webhook->method === 'POST' ? true : false) : true, ['id' => 'method-post', 'class' => 'form-check-input']) !!}
    {!! Form::label('method-post', 'POST', ['class' => 'form-check-label me-1']) !!}
    {!! Form::radio('method', 'GET', isset($webhook) ? ($webhook->method === 'GET' ? true : false) : false, ['id' => 'method-get', 'class' => 'form-check-input']) !!}
    {!! Form::label('method-get', 'GET', ['class' => 'form-check-label']) !!}
</div>

<div class="border rounded-3 container my-3 p-3">
    <h6 class="fw-bold text-secondary text-center">@lang('projects.notifications.webhooks.fields')</h6>
    <div class="row row-cols-3">
        @foreach($fields as $field)
            <div class="col my-1 mx-3">
                {!! Form::checkbox('fields[]', $field, isset($webhook) ? (in_array($field, $webhook->fields) ? true : false) : false, ['id' => $field . '_checkbox' ]) !!}
                {!! Form::label( $field . '_checkbox', trans("projects.notifications.webhooks.bitrix24.fields.$field") ) !!}                            
            </div>
        @endforeach
    </div>
</div>

<div class="text-center">
    {!! Form::submit(trans(isset($webhook) ? 'projects.button-save' : 'projects.button-add'), ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}