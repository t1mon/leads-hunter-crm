@extends('layouts.project')

@section('content')

<div id="tabs">
    <ul class="nav nav-tabs">
        <li class="nav_item">
            <a class="nav-link active" data-toggle="tab" href="#forward-log">@lang('projects.notifications.tab_forward_log')</a>
        </li>
        <li class="nav_item">
            <a class="nav-link" data-toggle="tab" href="#email-settings">@lang('projects.notifications.tab_email_settings')</a>
        </li>
    </ul>
</div>

<div class="tab-content" id="content">
    <!--Журнал рассылок-->
    <div class="tab-pane fade show active" id="forward-log">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-dark ">
                <thead>
                    <tr>
                        <th>@lang('projects.notifications.date')</th>
                        <th>@lang('projects.notifications.email_notification')</th>
                        <th>@lang('projects.notifications.telegram_notification')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($notifications) > 0)
                        @foreach($notifications as $notification)
                            <tr>
                                <td class="text-nowrap">{{$notification->created_at}}</td>
                                <td class="text-nowrap"><strong>(В РАБОТЕ)</strong></td>
                                <td class="text-nowrap"><strong>(В РАБОТЕ)</strong></td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan='3' class="text-nowrap">@lang('projects.notifications.none_available')</td>
                            </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!--Настройки рассылок по e-mail-->
    <div class="tab-pane fade" id="email-settings">
        <!--Форма настроек e-mail-->
        <div>
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
                        <li>{!! Form::checkbox('settings[email][fields][]', 'email', in_array('email', $project->settings['email']['fields']) ? true : false) !!} @lang('projects.journal.email')</li>
                        <li>{!! Form::checkbox('settings[email][fields][]', 'city', in_array('city', $project->settings['email']['fields']) ? true : false) !!} @lang('projects.journal.city')</li>
                        <li>{!! Form::checkbox('settings[email][fields][]', 'host', in_array('host', $project->settings['email']['fields']) ? true : false) !!} @lang('projects.journal.host')</li>
                    </ul>
                    {!! Form::button(trans('projects.notifications.emails_save'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
                {!! Form::close()!!}
            </div>
        </div>

        <!--Форма добавления новых e-mail-->
        <div>
            {!! Form::open(['route' => ['email.store', $project], 'class' => 'd-flex']) !!}
            <div class="form-group col-md-12">
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('projects.notifications.emails_form_placeholder')]) !!}
                    {!! Form::hidden('project_id', $project->id) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::button(trans('projects.notifications.emails_button_add'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>

        <!--Список e-mail-->
        <div class="table-responsive" id="email-settings-list">
            <table class="table table-striped table-bordered table-dark ">
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


</div>

@endsection

