@extends('material-dashboard.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => isset($webhook) ? ['webhook.update', $project, $webhook->name] : ['webhook.store', $project], 'method' => isset($webhook) ? 'PUT' : 'CREATE']) !!}
            {!! Form::hidden('form', 'extended') !!}
            {!! Form::hidden('enabled', true) !!}
            {!! Form::hidden('type', 'amocrm') !!}
            {!! Form::hidden('method', 'POST') !!}
            
            <div class="row justify-content-center mb-5">
                <h5 class="card-title text-center">Общие настройки</h5>
                {{--Название вебхука--}}
                <div class="col-4 text-center">
                    {!! Form::label('webhook_name', 'Название вебхука', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('name', null,
                            [
                                'class' => 'form-control',
                                'placeholder' => trans('projects.notifications.webhooks.name'),
                                'id' => 'webhook_name'
                            ])
                        !!}
                    </div>
                </div>
                
                {{--URL вебхука--}}
                <div class="col-4 text-center">
                    {!! Form::label('webhook_url', 'URL вебхука', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('url', null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'URL вебхука',
                                'id' => 'webhook_url'
                            ])
                        !!}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-2">
                <h5 class="card-title text-center">Настройки OAuth</h5>
                {{--redirect_uri--}}
                <div class="col-4 text-center">
                    {!! Form::label('redirect_uri', 'redirect_uri', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('redirect_uri', null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'redirect_uri',
                                'id' => 'redirect_uri'
                            ])
                        !!}
                    </div>
                </div>

                {{--Код авторизации--}}
                <div class="col-4 text-center">
                    {!! Form::label('authorization_code', 'Код авторизации', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('authorization_code', null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'Код авторизации',
                                'id' => 'authorization_code'
                            ])
                        !!}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-5">
                {{--client_id--}}
                <div class="col-4 text-center">
                    {!! Form::label('client_id', 'client_id', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('client_id', null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'client_id',
                                'id' => 'client_id'
                            ])
                        !!}
                    </div>
                </div>

                
                {{--client_secret--}}
                <div class="col-4 text-center">
                    {!! Form::label('client_secret', 'client_secret', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('client_secret', null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'client_secret',
                                'id' => 'client_secret'
                            ])
                        !!}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <h5 class="card-title text-center">Настройки запроса</h5>

                {{--Тело запроса--}}
                <div class="col-6 text-center">
                    {!! Form::label('webhook_query', 'Тело запроса в разметке YAML', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 container my-3 p-3 align-middle">
                        {!! Form::textarea('query', null,
                        [
                            'id' => 'webhook_query',
                            'placeholder' => 'Введите параметры запроса в разметке YAML',
                            'class' => 'form-control',
                        ]
                        )!!}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-3 text-center">
                    {!! Form::submit(trans(isset($webhook) ? 'projects.button-save' : 'projects.button-add'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
    
            {!! Form::close() !!}
        </div>
    </div>

@endsection