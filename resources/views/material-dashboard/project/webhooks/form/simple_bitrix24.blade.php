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

            <input type="radio" id="method-post" name="method" value="POST" {{isset($webhook) ? ($webhook->method === 'POST' ? 'checked' : '') : 'checked'}} class="form-check-input">
            <label for="method-post" class="form-check-label me-1">POST</label>
            <input type="radio" id="method-get" name="method" value="GET" {{isset($webhook) ? ($webhook->method === 'GET' ? 'checked' : '') : ''}} class="form-check-input">
            <label for="method-get" class="form-check-label">GET</label>       
        
            {{-- {!! Form::radio('method', 'POST', isset($webhook) ? ($webhook->method === 'POST' ? true : false) : true, ['class' => 'form-check-input', 'id' => 'method-post']) !!}
            {!! Form::label('method-post', 'POST', ['class' => 'form-check-label me-1']) !!}
            {!! Form::radio('method', 'GET', isset($webhook) ? ($webhook->method === 'GET' ? true : false) : false, ['class' => 'form-check-input', 'id' => 'method-get']) !!}
            {!! Form::label('method-get', 'GET', ['class' => 'form-check-label']) !!} --}}
        </div>

        <div class="my-3">
            {!! Form::label('fields[TITLE]', 'Название лида (TITLE):', ['class' => 'form-label my-0']) !!}
            {!! Form::text('fields[fields][TITLE]', 
                isset($webhook) ? (array_key_exists('TITLE', $webhook_fields['fields']) ? $webhook_fields['fields']['TITLE'] : null) : null, 
                ['class' => 'form-control border rounded-3 p-2 w-25']) !!}
        </div>

        <div class="my-3">
            {!! Form::label('fields[SOURCE_ID]', 'Название источника (SOURCE_ID):', ['class' => 'form-label my-0']) !!}
            {!! Form::text('fields[fields][SOURCE_ID]', 
                isset($webhook) ? (array_key_exists('TITLE', $webhook_fields['fields']) ? $webhook_fields['fields']['SOURCE_ID'] : null) : null, 
                ['class' => 'form-control border rounded-3 p-2 w-25']) !!}
        </div>

        <div class="my-3">
            {!! Form::label('fields[SOURCE_DESCRIPTION]', 'Описание источника (SOURCE_DESCRIPTION):', ['class' => 'form-label my-0']) !!}
            {!! Form::text('fields[fields][SOURCE_DESCRIPTION]',
                isset($webhook) ? (array_key_exists('SOURCE_DESCRIPTION', $webhook_fields['fields']) ? $webhook_fields['fields']['SOURCE_DESCRIPTION'] : null) : null, 
                ['class' => 'form-control border rounded-3 p-2 w-50']) !!}
        </div>

        <h6 class="card-title text-center">Поля</h6>

        <div class="border round-3 my-3">
            <div class="my-4 p-2 row">
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][NAME]" id="fields[fields][NAME]" value="$name" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('NAME', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        @lang('projects.notifications.webhooks.bitrix24.fields.NAME')
                    </label>
                    
                    {{-- {!! Form::checkbox('fields[fields][NAME]', '$name',
                                            isset($webhook) ? (array_key_exists('NAME', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[NAME]'])
                        !!}
                    {!! Form::label('fields[NAME]', trans('projects.notifications.webhooks.bitrix24.fields.NAME'), ['class' => 'form-check-label']) !!} --}}
                </div>
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][SECOND_NAME]" id="fields[fields][SECOND_NAME]" value="$patronymic" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('SECOND_NAME', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        @lang('projects.notifications.webhooks.bitrix24.fields.SECOND_NAME')
                    </label>

                    {{-- {!! Form::checkbox('fields[fields][SECOND_NAME]', '$patronymic',
                                            isset($webhook) ? (array_key_exists('SECOND_NAME', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[SECOND_NAME]'])
                        !!}
                    {!! Form::label('fields[fields][SECOND_NAME]', trans('projects.notifications.webhooks.bitrix24.fields.SECOND_NAME'), ['class' => 'form-check-label']) !!} --}}
                </div>
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][LAST_NAME]" id="fields[fields][LAST_NAME]" value="$surname" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('LAST_NAME', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        @lang('projects.notifications.webhooks.bitrix24.fields.LAST_NAME')
                    </label>

                    {{-- {!! Form::checkbox('fields[fields][LAST_NAME]', '$surname',
                                            isset($webhook) ? (array_key_exists('LAST_NAME', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[LAST_NAME]'])
                        !!}
                    {!! Form::label('fields[fields][LAST_NAME]', trans('projects.notifications.webhooks.bitrix24.fields.LAST_NAME'), ['class' => 'form-check-label']) !!} --}}
                </div>
            </div>

            <div class="my-4 p-2 row">
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][OPPORTUNITY]" id="fields[fields][OPPORTUNITY]" value="$cost" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('OPPORTUNITY', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        @lang('projects.notifications.webhooks.bitrix24.fields.OPPORTUNITY')
                    </label>

                    {{-- {!! Form::checkbox('fields[fields][OPPORTUNITY]', '$cost',
                                            isset($webhook) ? (array_key_exists('OPPORTUNITY', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[OPPORTUNITY]'])
                        !!}
                    {!! Form::label('fields[fields][OPPORTUNITY]', trans('projects.notifications.webhooks.bitrix24.fields.OPPORTUNITY'), ['class' => 'form-check-label']) !!} --}}
                </div>
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][PHONE][0][VALUE]" id="fields[fields][PHONE][0][VALUE]" value="$phone" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('VALUE', $webhook_fields['fields']['PHONE'][0]) ? 'checked' : '') : ''}}>
                        @lang('projects.notifications.webhooks.bitrix24.fields.PHONE')
                    </label>
                    
                    {!! Form::hidden('fields[fields][PHONE][0][VALUE_TYPE]', 'WORK') !!}
                    {{-- {!! Form::checkbox('fields[fields][PHONE][0][VALUE]', '$phone',
                                            isset($webhook) ? (array_key_exists('VALUE', $webhook_fields['fields']['PHONE'][0]) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[fields][PHONE][VALUE]'])
                        !!}
                    {!! Form::label('fields[fields][PHONE][0][VALUE]', trans('projects.notifications.webhooks.bitrix24.fields.PHONE'), ['class' => 'form-check-label']) !!} --}}
                </div>
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][EMAIL][0][VALUE]" id="fields[fields][EMAIL][0][VALUE]" value="$email" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('VALUE', $webhook_fields['fields']['EMAIL'][0]) ? 'checked' : '') : ''}}>
                        @lang('projects.notifications.webhooks.bitrix24.fields.EMAIL')
                    </label>
                    {!! Form::hidden('fields[fields][EMAIL][0][VALUE_TYPE]', 'WORK') !!}
                    {{-- {!! Form::checkbox('fields[fields][EMAIL][0][VALUE]', '$email',
                                            isset($webhook) ? (array_key_exists('VALUE', $webhook_fields['fields']['EMAIL'][0]) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[EMAIL][0][VALUE]'])
                        !!}
                    {!! Form::label('fields[fields][EMAIL][0][VALUE]', trans('projects.notifications.webhooks.bitrix24.fields.EMAIL'), ['class' => 'form-check-label']) !!} --}}
                </div>
            </div>
            
            <div class="my-4 p-2 row">
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][ADDRESS_CITY]" id="fields[fields][ADDRESS_CITY]" value="$city" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('ADDRESS_CITY', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        @lang('projects.notifications.webhooks.bitrix24.fields.ADDRESS_CITY')
                    </label>
                    {{-- {!! Form::checkbox('fields[fields][ADDRESS_CITY]', '$city',
                                            isset($webhook) ? (array_key_exists('ADDRESS_CITY', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[ADDRESS_CITY]'])
                        !!}
                    {!! Form::label('fields[fields][ADDRESS_CITY]', trans('projects.notifications.webhooks.bitrix24.fields.ADDRESS_CITY'), ['class' => 'form-check-label']) !!} --}}
                </div>
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][UTM_SOURCE]" id="fields[fields][UTM_SOURCE]" value="$utm_source" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('UTM_SOURCE', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        UTM_SOURCE
                    </label>
                    {{-- {!! Form::checkbox('fields[fields][UTM_SOURCE]', '$utm_source',
                                            isset($webhook) ? (array_key_exists('UTM_SOURCE', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[UTM_SOURCE]'])
                        !!}
                    {!! Form::label('fields[fields][UTM_SOURCE]', 'UTM_SOURCE', ['class' => 'form-check-label']) !!} --}}
                </div>
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][UTM_CAMPAIGN]" id="fields[fields][UTM_CAMPAIGN]" value="$utm_campaign" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('UTM_CAMPAIGN', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        UTM_CAMPAIGN
                    </label>

                    {{-- {!! Form::checkbox('fields[fields][UTM_CAMPAIGN]', '$utm_campaign',
                                            isset($webhook) ? (array_key_exists('UTM_CAMPAIGN', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[UTM_CAMPAIGN]'])
                        !!}
                    {!! Form::label('fields[fields][UTM_CAMPAIGN]', 'UTM_CAMPAIGN', ['class' => 'form-check-label']) !!} --}}
                </div>
            </div>

            <div class="my-4 p-2 row">
                <div class="col form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="fields[fields][UTM_CONTENT]" id="fields[fields][UTM_CONTENT]" value="$utm_content" class="form-check-input"
                        {{isset($webhook) ? (array_key_exists('UTM_CONTENT', $webhook_fields['fields']) ? 'checked' : '') : ''}}>
                        UTM_CONTENT
                    </label>

                    {{-- {!! Form::checkbox('fields[fields][UTM_CONTENT]', '$utm_content',
                                            isset($webhook) ? (array_key_exists('UTM_CONTENT', $webhook_fields['fields']) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => 'fields[UTM_CONTENT]'])
                        !!}
                    {!! Form::label('fields[fields][UTM_CONTENT]', 'UTM_CONTENT', ['class' => 'form-check-label']) !!} --}}
                </div>
            </div>
            {!! Form::hidden('fields[fields][STATUS_ID]', 'NEW') !!}
            {!! Form::hidden('fields[fields][OPENED]', 'Y') !!}
            {!! Form::hidden('fields[fields][params][REGISTER_SONET_EVENT]', 'Y') !!}
        </div>

        <div class="text-center">
            {!! Form::submit(trans(isset($webhook) ? 'projects.button-save' : 'projects.button-add'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}

    </div>

    <div class="card-footer">
        <p class="card-text text-center">
            <a href="{{isset($webhook) ? route('webhook.edit', [$project, $webhook->name, 'form' => 'extended']) : route('webhook.create', ['project' => $project, 'form' => 'extended'])}}">
                Расширенная форма
            </a>
        </p>
    </div>
</div>

@endsection