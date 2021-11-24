{{--Поля--}}
@php
    $fields = ['name', 'surname', 'patronymic', 'phone', 'entries', 'email', 'cost', 'comment', 'city', 'host', 'ip', 'referrer', 'url_query_string'];
@endphp

{{--Форма для добавления вебхука--}}
<div class="card align-self-center my-3">
    <div class="card-body">
        <h6 class="card-title text-center">@lang('projects.notifications.webhooks.add')</h6>

        {!! Form::open(['route' => ['webhook.store', $project] ]) !!}
            <div class="border rounded-3 align-middle my-3 p-3">
                <div class="border-bottom my-2">
                    {!! Form::hidden('enabled', true) !!}
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
                <div class="row row-cols-6">
                    @foreach($fields as $field)
                        <div class="col my-1 mx-3">
                            {!! Form::checkbox('fields[]', $field, false, ['id' => $field . '_checkbox' ]) !!}
                            {!! Form::label( $field . '_checkbox', trans('projects.journal.' . $field) ) !!}                            
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

{{--Список доступных вебхуков--}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('projects.notifications.webhooks.name')</th>
                        <th>@lang('projects.notifications.webhooks.method')</th>
                        <th>@lang('projects.notifications.webhooks.url')</th>
                        <th>@lang('projects.notifications.webhooks.fields')</th>
                        <th colspan="2">@lang('projects.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($project->webhooks)
                        @foreach($project->webhooks as $webhook)
                            <tr class="{{$webhook->enabled ? '' : 'text-decoration-line-through'}}">
                                <td>{{$loop->iteration}}</td>
                                <td class="{{$webhook->enabled ? 'text-success' : 'text-secondary'}}">{{$webhook->name}}</td>
                                <td class="{{$webhook->enabled ? 'text-dark' : 'text-secondary'}}">{{$webhook->method}}</td>
                                <td class="{{$webhook->enabled ? 'text-warning' : 'text-secondary'}}">{{$webhook->url}}</td>
                                <td  class="{{$webhook->enabled ? 'text-info' : 'text-secondary'}}">
                                    @foreach ($webhook->fields as $field)
                                        {{ trans('projects.journal.' . $field)  . ($loop->last ? '' : ', ') }}
                                    @endforeach
                                </td>
                                <td>
                                    {!! Form::open([ 'route' => ['webhook.update', $project, $webhook->name ], 'method' => 'PUT' ]) !!}
                                        {!! Form::hidden('enabled', $webhook->enabled ? 0 : 1) !!}
                                        {!! Form::button('<i class="fa fa-power-off" aria-hidden="true"></i>', ['class' => 'btn btn-' . ($webhook->enabled ? 'primary' : 'secondary'), 'type' => 'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                                <td>
                                    {!! Form::open([ 'route' => ['webhook.destroy', $project, $webhook->name ], 'method' => 'DELETE' ]) !!}
                                        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-' . ($webhook->enabled ? 'danger' : 'secondary'), 'type' => 'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">@lang('projects.notifications.webhooks.none')</td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>