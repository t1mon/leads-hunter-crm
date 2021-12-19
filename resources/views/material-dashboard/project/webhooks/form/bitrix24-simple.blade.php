@extends('material-dashboard.layouts.app')

@section('content')

<div class="card">
    <h6 class="card-header text-center">
        @lang('projects.notifications.webhooks.add')
    </h6>

    <div class="card-body">
        {!! Form::open(['route' => isset($webhook) ? ['webhook.update', $project, $webhook->name] : ['webhook.store', $project], 'method' => isset($webhook) ? 'PUT' : 'CREATE']) !!}
        {!! Form::hidden('enabled', true) !!}
        {!! Form::hidden('type', 'bitrix24') !!}
        {!! Form::hidden('form', 'simple_bitrix24') !!}

        <div class="my-3">
            {!! Form::label('name', trans('projects.notifications.webhooks.name'), ['class' => 'form-label my-0']) !!}
            {!! Form::text('name', isset($webhook) ? $webhook->name : null, ['class' => 'form-control border rounded-3 p-2 w-50']) !!}    
        </div>
        
        <div class="my-3">
            {!! Form::label('url', trans('projects.notifications.webhooks.url'), ['class' => 'form-label my-0']) !!}
            {!! Form::text('url', isset($webhook) ? $webhook->url : null, ['class' => 'form-control border rounded-3 p-2 w-50', 'placeholder' => trans('projects.notifications.webhooks.placeholder')]) !!}    
        </div>

        <div class="border rounded-3 form-check my-3 p-3 text-center">
            <span class="fw-bold me-3">@lang('projects.notifications.webhooks.method'):</span>
        
            {!! Form::radio('method', 'POST', isset($webhook) ? ($webhook->method === 'POST' ? true : false) : true, ['class' => 'form-check-input', 'id' => 'method-post']) !!}
            {!! Form::label('method-post', 'POST', ['class' => 'form-check-label me-1']) !!}
            {!! Form::radio('method', 'GET', isset($webhook) ? ($webhook->method === 'GET' ? true : false) : false, ['class' => 'form-check-input', 'id' => 'method-get']) !!}
            {!! Form::label('method-get', 'GET', ['class' => 'form-check-label']) !!}
        </div>

        <div class="my-3">
            {!! Form::label('fields[TITLE]', 'Название лида (TITLE):', ['class' => 'form-label my-0']) !!}
            {!! Form::text('fields[TITLE]', null, ['class' => 'form-control border rounded-3 p-2 w-15']) !!}
        </div>

        <div class="my-3">
            {!! Form::label('fields[SOURCE_ID]', 'Номер источника (SOURCE_ID):', ['class' => 'form-label my-0']) !!}
            {!! Form::text('fields[SOURCE_ID]', null, ['class' => 'form-control border rounded-3 p-2 w-25']) !!}
        </div>

        <div class="my-3">
            {!! Form::label('fields[SOURCE_DESCRIPTION]', 'Описание источника (SOURCE_DESCRIPTION):', ['class' => 'form-label my-0']) !!}
            {!! Form::text('fields[SOURCE_DESCRIPTION]', null, ['class' => 'form-control border rounded-3 p-2 w-50']) !!}
        </div>

        <h6 class="card-title text-center">Поля</h6>

        <div class="border round-3 my-3">
            <div class="my-4 p-2 row">
                <div class="col form-check">
                    {!! Form::checkbox('fields[NAME]', '$name',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[NAME]'])
                        !!}
                    {!! Form::label('fields[NAME]', trans('projects.notifications.webhooks.bitrix24.fields.NAME'), ['class' => 'form-check-label']) !!}
                </div>
                <div class="col form-check">
                    {!! Form::checkbox('fields[SECOND_NAME]', '$patronymic',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[SECOND_NAME]'])
                        !!}
                    {!! Form::label('fields[SECOND_NAME]', trans('projects.notifications.webhooks.bitrix24.fields.SECOND_NAME'), ['class' => 'form-check-label']) !!}
                </div>
                <div class="col form-check">
                    {!! Form::checkbox('fields[LAST_NAME]', '$surname',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[LAST_NAME]'])
                        !!}
                    {!! Form::label('fields[LAST_NAME]', trans('projects.notifications.webhooks.bitrix24.fields.LAST_NAME'), ['class' => 'form-check-label']) !!}
                </div>
            </div>

            <div class="my-4 p-2 row">
                <div class="col form-check">
                    {!! Form::checkbox('fields[OPPORTUNITY]', '$cost',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[OPPORTUNITY]'])
                        !!}
                    {!! Form::label('fields[OPPORTUNITY]', trans('projects.notifications.webhooks.bitrix24.fields.OPPORTUNITY'), ['class' => 'form-check-label']) !!}
                </div>
                <div class="col form-check">
                    {!! Form::hidden('fields[PHONE][VALUE_TYPE]', 'WORK') !!}
                    {!! Form::checkbox('fields[PHONE][VALUE]', '$phone',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[PHONE][VALUE]'])
                        !!}
                    {!! Form::label('fields[PHONE][VALUE]', trans('projects.notifications.webhooks.bitrix24.fields.PHONE'), ['class' => 'form-check-label']) !!}
                </div>
                <div class="col form-check">
                    {!! Form::hidden('fields[EMAIL][VALUE_TYPE]', 'WORK') !!}
                    {!! Form::checkbox('fields[EMAIL][VALUE]', '$email',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[EMAIL][VALUE]'])
                        !!}
                    {!! Form::label('fields[EMAIL][VALUE]', trans('projects.notifications.webhooks.bitrix24.fields.EMAIL'), ['class' => 'form-check-label']) !!}
                </div>
            </div>
            
            <div class="my-4 p-2 row">
                <div class="col form-check">
                    {!! Form::checkbox('fields[ADDRESS_CITY]', '$city',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[ADDRESS_CITY]'])
                        !!}
                    {!! Form::label('fields[ADDRESS_CITY]', trans('projects.notifications.webhooks.bitrix24.fields.ADDRESS_CITY'), ['class' => 'form-check-label']) !!}
                </div>
                <div class="col form-check">
                    {!! Form::checkbox('fields[UTM_SOURCE]', '$utm_source',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[UTM_SOURCE]'])
                        !!}
                    {!! Form::label('fields[UTM_SOURCE]', 'UTM_SOURCE', ['class' => 'form-check-label']) !!}
                </div>
                <div class="col form-check">
                    {!! Form::checkbox('fields[UTM_CAMPAIGN]', '$utm_campaign',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[UTM_CAMPAIGN]'])
                        !!}
                    {!! Form::label('fields[UTM_CAMPAIGN]', 'UTM_CAMPAIGN', ['class' => 'form-check-label']) !!}
                </div>
            </div>

            <div class="my-4 p-2 row">
                <div class="col form-check">
                    {!! Form::checkbox('fields[UTM_CONTENT]', '$utm_content',
                                            false,
                                            ['class' => 'form-check-input', 'id' => 'fields[UTM_CONTENT]'])
                        !!}
                    {!! Form::label('fields[UTM_CONTENT]', 'UTM_CONTENT', ['class' => 'form-check-label']) !!}
                </div>
            </div>
            {!! Form::hidden('fields[STATUS_ID]', 'NEW') !!}
            {!! Form::hidden('fields[OPENED]', 'Y') !!}
            {!! Form::hidden('fields[params][REGISTER_SONET_EVENT]', 'Y') !!}
        </div>

        <div class="text-center">
            {!! Form::submit(trans(isset($webhook) ? 'projects.button-save' : 'projects.button-add'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}

    </div>

    <div class="card-footer">
        <p class="card-text text-center">
            <a href="{{route('webhook.create', ['project' => $project, 'form' => 'extended'])}}">
                Расширенная форма
            </a>
        </p>
    </div>
</div>

@endsection