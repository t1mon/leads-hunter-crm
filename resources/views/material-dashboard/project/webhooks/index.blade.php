{{--Поля--}}
@php
    $fields = ['name', 'surname', 'patronymic', 'phone', 'entries', 'email', 'cost', 'comment', 'city', 'host', 'ip', 'referrer', 'url_query_string'];
@endphp

{{--Форма для добавления вебхука--}}
<div class="card align-self-center my-3">
    <div class="card-body">
        <h6 class="card-title text-center">@lang('projects.notifications.webhooks.add')</h6>

        {!! Form::open() !!}
            <div class="my-3">
                {!! Form::text(
                    'name',
                    null,
                    [
                        'class' => 'form-control',
                        'placeholder' => trans('projects.notifications.webhooks.name'),
                    ]) !!}
            </div>

            <div class="my-3">
                {!! Form::text(
                    'url',
                    null,
                    [
                        'class' => 'form-control',
                        'placeholder' => trans('projects.notifications.webhooks.placeholder'),
                    ]) !!}
            </div>

            <div class="container my-3">
                <div class="row row-cols-3">
                    @foreach($fields as $field)
                        <div class="col my-1 mx-3">
                            {!! Form::hidden($field, 0) !!}
                            {!! Form::checkbox($field, $field, false, ['id' => $field . '_checkbox' ]) !!}
                            {!! Form::label( $field . '_checkbox', trans('projects.journal.' . $field) ) !!}
                            
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                {!! Form::submit(trans('projects.button-add'), ['class' => 'btn btn-primary']) !!}
            </div>
        {!! Form::close() !!}
    </div>
</div>

{{--Список доступных вебхуков--}}
<div class="table-responsive">
</div>