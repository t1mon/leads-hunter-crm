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
        
            {!! Form::radio('method', 'POST', isset($webhook) ? ($webhook->method === 'POST' ? true : false) : true, ['class' => 'form-check-input', 'id' => 'method-post']) !!}
            {!! Form::label('method-post', 'POST', ['class' => 'form-check-label me-1']) !!}
            {!! Form::radio('method', 'GET', isset($webhook) ? ($webhook->method === 'GET' ? true : false) : false, ['class' => 'form-check-input', 'id' => 'method-get']) !!}
            {!! Form::label('method-get', 'GET', ['class' => 'form-check-label']) !!}
        </div>

        <h6 class="card-title text-center">Поля</h6>
        
        <div class="border round-3 my-3">
            @foreach (array_chunk($fields, 2) as $field)
                <div class="my-4 p-2 row">
                    <div class="col form-check">
                        {!! Form::checkbox("fields[{$field[0]}]", "\${$field[0]}", 
                                        isset($webhook) ? (ststr($webhook->query, $field[0]) ? true : false) : false,
                                        ['class' => 'form-check-input', 'id' => "fields[{$field[0]}]"])
                        !!}
                        {!! Form::label("fields[{$field[0]}]", trans('projects.notifications.webhooks.common.fields.'.$field[0]), ['class' => 'form-check-label']) !!}
                    </div>
                    <div class="col form-check">
                        {!! Form::checkbox("fields[{$field[1]}]", "\${$field[1]}", 
                                            isset($webhook) ? (ststr($webhook->query, $field[1]) ? true : false) : false,
                                            ['class' => 'form-check-input', 'id' => "fields[{$field[1]}]"])
                        !!}
                        {!! Form::label("fields[{$field[1]}]", trans('projects.notifications.webhooks.common.fields.'.$field[1]), ['class' => 'form-check-label']) !!}
                    </div>
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
            <a href="{{route('webhook.create', ['project' => $project, 'form' => 'extended'])}}">
                Расширенная форма
            </a>
        </p>
    </div>
</div>

@endsection