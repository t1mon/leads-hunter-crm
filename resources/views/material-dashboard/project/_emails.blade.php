{{--Объявление полей журнала (чтобы быстро добавлять/удалять в дальнейшем--}}
@php
    $mailing_fields = ['email', 'city', 'cost', 'host', 'comment', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign', 'source'];
@endphp

<div class="container">
    <div class="row row-cols-2">
        {{--Форма настроек e-mail--}}
        <div class='col'>
            <div class="card my-3">
                <div class="card-body">
                    {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', [$project]] ]) !!}
                        <div class="border border-light rounded-3 my-3 p-2">
                            <h5 class="card-title text-center">@lang('projects.notifications.emails_settings')</h5>
                            <div class="my-1 form-check">
                                {!! Form::hidden('settings[email][enabled]', 0) !!}
                                {!! Form::checkbox('settings[email][enabled]', 1, $project->settings['email']['enabled'] ? true : false, ['id' => 'enabled_checkbox', 'class' => 'form-check-input']) !!}
                                {!! Form::label('enabled_checkbox', trans('projects.notifications.emails_toggle'), ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="my-2">
                                {!! Form::label('email-subject', trans('projects.notifications.emails_subject') . ':', ['class' => 'form-label']) !!}
                                {!! Form::text('settings[email][subject]', $project->settings['email']['subject'], ['class' => 'form-control border p-2', 'placeholder' => trans('projects.notifications.emails_subject'), 'id' => 'email-subject']) !!}
                            </div>
                            <div class="my-2 text-center form-check">
                                <p class="form-label text-center">Тип шаблона письма</p>
                                {!! Form::radio('settings[email][template]', 'text',
                                        $project->settings['email']['template'] === 'text' ? true : false,
                                    [
                                        'id' => 'template-text',
                                        'class' => 'form-check-input'
                                    ])
                                !!}
                                {!! Form::label('template-text', 'Текст', ['class' => 'form-check-label me-2']) !!}
                                {!! Form::radio('settings[email][template]', 'view',
                                        $project->settings['email']['template'] === 'view' ? true : false,
                                    [
                                        'id' => 'template-view',
                                        'class' => 'form-check-input'
                                    ])
                                !!}
                                {!! Form::label('template-view', 'Упрощённый', ['class' => 'form-check-label me-2']) !!}

                                {!! Form::radio('settings[email][template]', 'markdown',
                                        $project->settings['email']['template'] === 'markdown' ? true : false,
                                    [
                                        'id' => 'template-markdown',
                                        'class' => 'form-check-input',
                                    ])
                                !!}
                                {!! Form::label('template-markdown', 'Leads-Hunter', ['class' => 'form-check-label']) !!}
                            </div>
                        </div>

                        <div class="border border-light rounded-3 my-3 p-2">
                            {{--Поля в рассылке--}}
                            <h6 class="card-title text-center">@lang('projects.notifications.emails_fields')</h6>
                            @foreach(array_chunk($mailing_fields, 2) as $columns)
                                <div class="my-4 row">
                                    @foreach ($columns as $column)
                                        <div class="col form-check">
                                            <label class="form-label-check">
                                                <input type="checkbox" class="form-check-input" name="settings[email][fields][]" value="{{$column}}" {{in_array($column, $project->settings['email']['fields']) ? 'checked' : ''}}  id="{{$column}}-label">
                                                @lang('projects.notifications.webhooks.common.fields.' . $column)
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="my-3 text-center">
                                {!! Form::button(trans('projects.notifications.emails_save'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
                            </div>
                            {!! Form::close()!!}
                        </div>
                </div>
            </div>
        </div>

        {{--Форма добавления новых e-mail--}}
        <div class='col'>
            <div class="card my-3">
                <div class="card-body text-center">
                    <h6 class="card-title">@lang('projects.notifications.emails_add')</h6>
                    {!! Form::open(['route' => ['email.store', $project],]) !!}
                    {!! Form::hidden('project_id', $project->id) !!}
                    <div class="border-danger rounded-3 my-2">
                        {!! Form::text('email', null, ['class' => '', 'placeholder' => trans('projects.notifications.emails_form_placeholder')]) !!}
                    </div>
                    <div class="my-2">
                        {!! Form::submit(trans('projects.button-add'), ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

{{--Таблица с адресами--}}
<div class="card my-3">
    <div class="card-body">
        <h6 class="card-title text-center">@lang('projects.notifications.emails_list')</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-center">
                        <th>@lang('projects.notifications.emails_email')</th>
                        <th>@lang('projects.notifications.emails_action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($emails) > 0)
                        @foreach($emails as $email)
                            <tr>
                                <td>{{$email->email}}</strong></td>
                                <td class="text-center">
                                    {!! Form::model($email, ['method' => 'DELETE', 'route' => ['email.destroy', [$project,$email]]]) !!}
                                    {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'name' => 'submit', 'type' => 'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan='2' class="text-center">@lang('projects.notifications.emails_none')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
