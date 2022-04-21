@extends('material-dashboard.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => isset($webhook) ? ['webhook.update', $project, $webhook->name] : ['webhook.store', $project], 'method' => isset($webhook) ? 'PUT' : 'CREATE']) !!}
            {!! Form::hidden('form', 'extended') !!}
            {!! Form::hidden('enabled', true) !!}
            {!! Form::hidden('type', 'amocrm') !!}
            {!! Form::hidden('method', 'POST') !!}
            {!! Form::hidden('auth_url', $webhook->auth_url ?? null) !!}
            
            <div class="row justify-content-center mb-5">
                <h5 class="card-title text-center">Общие настройки</h5>
                {{--Название вебхука--}}
                <div class="col-4 text-center">
                    {!! Form::label('webhook_name', 'Название вебхука', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('name', $webhook->name ?? null,
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
                        {!! Form::text('url', $webhook->url ?? null,
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
                        {!! Form::text('redirect_uri', $webhook->redirect_uri ?? null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'redirect_uri',
                                'id' => 'redirect_uri'
                            ])
                        !!}
                    </div>
                </div>

                {{--client_id--}}
                <div class="col-4 text-center">
                    {!! Form::label('client_id', 'client_id', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('client_id', $webhook->client_id ?? null,
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
                        {!! Form::text('client_secret', $webhook->client_secret ?? null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'client_secret',
                                'id' => 'client_secret'
                            ])
                        !!}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-5">
                {{--access_token--}}
                <div class="col-4 text-center">
                    {!! Form::label('access_token', 'access_token', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('access_token', $webhook->access_token ?? null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'access_token',
                                'id' => 'access_token'
                            ])
                        !!}
                    </div>
                </div>

                {{--refresh_token--}}
                <div class="col-4 text-center">
                    {!! Form::label('refresh_token', 'refresh_token', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 p-1">
                        {!! Form::text('refresh_token', $webhook->refresh_token ?? null,
                            [
                                'class' => 'form-control',
                                'placeholder' => 'refresh_token',
                                'id' => 'refresh_token'
                            ])
                        !!}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mb-5">
                {{--Кнопка вызова модалки для повторной авторизации--}}
                <div class="col-4 text-center">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reauth_modal">
                        Повторная авторизация
                    </button>
                </div>
            </div>

            <div class="row justify-content-center">
                <h5 class="card-title text-center">Настройки запроса</h5>

                {{--Тело запроса--}}
                <div class="col-6 text-center">
                    {!! Form::label('webhook_query', 'Тело запроса в разметке YAML', ['class' => 'form-label']) !!}
                    <div class="border rounded-3 container my-3 p-3 align-middle">
                        {!! Form::textarea('query', $webhook->query ?? null,
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

    {{--Модалка для повторной авторизации--}}
    <div class="modal fade" id="reauth_modal" tabindex="-1" aria-labelledby="reauth_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title">Введите код авторизации</h5>
                </div>
                
                <div class="modal-body">
                    <div class="border border-secondary rounded-3 p-1 px-2 my-2">
                        {!! Form::open(['route' => ['webhook.amocrm_reauthorize', $project], 'method' => 'POST', 'id' => 'reauthorize_form']) !!}
                            {!! Form::text('authorization_code', null, ['class' => 'form-control']) !!}
                            {!! Form::hidden('webhook_name', $webhook->name ?? null) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        @lang('projects.button-cancel')
                    </button>
                    <button form="reauthorize_form" type="submit" class="btn btn-primary">
                        Отправить
                    </button>
                </div>
    
            </div>
        </div>
    </div>

@endsection