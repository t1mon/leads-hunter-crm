<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title text-center">Настройки SMS-рассылки</h5>
        {!! Form::model($project, [ 'route' => ['project.update', $project], 'method' => 'PUT' ]) !!}

        {{--Переключатель ВКЛ/ВЫКЛ--}}
        <div class="border border-light rounded-3 my-3 p-2">
            <div class="form-check form-switch ps-2">
                {!! Form::hidden('settings[SMS][enabled]', 0) !!}
                {!! Form::checkbox(
                    'settings[SMS][enabled]',
                    1,
                    $project->settings['SMS']['enabled'] ? true : false,
                    [
                        'id' => 'sms_toggle',
                        'class' => 'form-check-input ms-auto',
                    ]
                ) !!}

                {!! Form::label("sms_toggle", "Включить отправку SMS на номер лида", ['class' => 'ms-3']) !!}
            </div>
        </div>

        {{--Текст SMS-сообщения--}}
        <div class="border border-light rounded-3 my-3 p-2">
            <div class="form-check form-switch ps-2">
                {!! Form::label('email-text', 'Текст сообщения', ['class' => 'form-label']) !!}
                {!! Form::textarea(
                    'settings[SMS][text]',
                    $project->settings['SMS']['text'],
                    [
                        'id' => 'email-text',
                        'placeholder' => 'Текст сообщения',
                        'class' => 'form-control border p-2 my-1 w-25'
                    ]
                ) !!}
                {!! Form::submit("Сохранить", ['class' => 'btn btn-primary my-1']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>