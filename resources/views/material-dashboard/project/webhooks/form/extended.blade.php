@extends('material-dashboard.layouts.app')

@section('content')

    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => isset($webhook) ? ['webhook.update', $project, $webhook->name] : ['webhook.store', $project], 'method' => isset($webhook) ? 'PUT' : 'CREATE']) !!}
            <div class="border rounded-3 align-middle my-3 p-3">
                <div class="border-bottom my-2">
                    {!! Form::hidden('form', 'extended') !!}

                    {!! Form::hidden('enabled', true) !!}

                    {!! Form::text(
                        'name',
                        $webhook->name ?? null,
                        [
                            'class' => 'form-control',
                            'placeholder' => trans('projects.notifications.webhooks.name'),
                        ]) !!}
                </div>

                <div class="border-bottom my-2">
                    {!! Form::text(
                        'type',
                        $webhook->type ?? null,
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Тип вебхука',
                            'id' => 'type',
                        ])
                    !!}
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

            <div class="row justify-content-center">
                <div class="col border rounded-3 form-check my-3 me-2 p-3 text-center">
                    <input type="checkbox" name="as_form" id="as_form" value=1 {{( isset($webhook->as_form) && $webhook->as_form === "1") ? 'checked' : '' }} class="form-check-input me-2">
                    <label for="as_form" class="form-check-label">Отправлять данные <span class="fw-bold">'application/x-www-form-urlencoded'</span></label>
                </div>

                <div class="col border rounded-3 form-check my-3 p-3 text-center">
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
            </div>

            <div class="border rounded-3 container my-3 p-3 align-middle">
                <h6 class="fw-bold text-secondary text-center">Параметры запроса</h6>
                {!! Form::label('form-control', 'Введите параметры запроса в разметке YAML:', ['class' => 'form-label'])!!}
                {!! Form::textarea('query', isset($webhook) ? $webhook->query : null,
                    [
                        'id' => 'query',
                        'class' => 'form-control border rounded-3 p-2',
                    ]
                )!!}

            </div>

            <div class="text-center">
                {!! Form::submit(trans(isset($webhook) ? 'projects.button-save' : 'projects.button-add'), ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>

    </div>

@endsection
