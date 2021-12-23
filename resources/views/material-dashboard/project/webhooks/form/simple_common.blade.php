@extends('material-dashboard.layouts.app')

@section('content')

{{--Поля--}}
@php
    $fields = ['name', 'surname', 'patronymic', 'phone', 'email', 'cost', 'city', 'host', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign'];
@endphp

<div class="card">
    <h6 class="card-header text-center">
        @lang('projects.notifications.webhooks.add')
    </h6>

    <div class="card-body">
        {!! Form::open(['route' => isset($webhook) ? ['webhook.update', $project, $webhook->name] : ['webhook.store', $project], 'method' => isset($webhook) ? 'PUT' : 'CREATE']) !!}
        {!! Form::hidden('enabled', true) !!}
        {!! Form::hidden('type', 'common') !!}
        {!! Form::hidden('form', 'simple_common') !!}

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

        <h6 class="card-title text-center">Поля</h6>
        
        <div class="border round-3 my-3">
            @foreach (array_chunk($fields, 2) as $field)
                <div class="row my-2">
                    @foreach ($field as $column)
                        <div class="col form-check">
                            <label class="form-check-label">
                                <input type="checkbox" name="fields[{{$column}}]" id="fields[{{$column}}]" value="${{$column}}" class="form-check-input"
                                {{isset($webhook) ? (array_key_exists($column, $webhook_fields) ? 'checked' : '') : ''}}>
                                @lang('projects.notifications.webhooks.common.fields.'.$column)
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
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