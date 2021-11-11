{{--Объявление полей журнала (чтобы быстро добавлять/удалять в дальнейшем--}}
@php
    $mailing_fields = ['email', 'city', 'host'];
@endphp

{{--Форма настроек e-mail--}}
<div class="card">
    <div>
        <h1>@lang('projects.notifications.emails_settings')</h1>
        {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', [$project]] ]) !!}
            <p>
                {!! Form::hidden('settings[email][enabled]', 0) !!}
                {!! Form::checkbox('settings[email][enabled]', 1, $project->settings['email']['enabled'] ? true : false) !!}
                @lang('projects.notifications.emails_toggle')
            </p>
            <h2>@lang('projects.notifications.emails_fields')</h2>
            <ul>
                @foreach($mailing_fields as $field)
                    <li>{!! Form::checkbox('settings[email][fields][]', $field, in_array($field, $project->settings['email']['fields']) ? true : false) !!} @lang('projects.journal.' . $field)</li>
                @endforeach
            </ul>
            {!! Form::button(trans('projects.notifications.emails_save'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
        {!! Form::close()!!}
    </div>
</div>

{{--Форма добавления новых e-mail--}}
<div class="card">
    {!! Form::open(['route' => ['email.store', $project], 'class' => 'd-flex']) !!}
    <div class="form-row">
        <div class="form-group col-md-12">
                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('projects.notifications.emails_form_placeholder')]) !!}
                {!! Form::hidden('project_id', $project->id) !!}
        </div>
        <div class="form-group col-md-12">
            {!! Form::button(trans('projects.notifications.emails_button_add'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{--Таблица с адресами--}}
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>@lang('projects.notifications.emails_email')</th>
                    <th>@lang('projects.notifications.emails_action')</th>
                </tr>
            </thead>
            <tbody>
                @if(count($emails) > 0)
                    @foreach($emails as $email)
                        <tr>
                            <td class="text-nowrap">{{$email->email}}</strong></td>
                            <td class="text-nowrap">
                                {!! Form::model($email, ['method' => 'DELETE', 'route' => ['email.destroy', [$project,$email]]]) !!}
                                {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'name' => 'submit', 'type' => 'submit']) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan='2' class="text-nowrap">@lang('projects.notifications.emails_none')</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>