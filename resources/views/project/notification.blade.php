@extends('layouts.project')

@section('content')

<div>
    <p>
        {!! Form::open(['route' => ['project.notification-toggle', $project], 'class' => 'd-flex']) !!}
            {!! 
            Form::button(trans($project->notifications_enabled ? 'projects.notifications.notifications_enabled' : 'projects.notifications.notifications_disabled'), 
                            ['class' => $project->notifications_enabled ? 'btn btn-primary' : "btn btn-danger", 
                             'type' => "submit",
                            ]) 
            !!}
        {!! Form::close() !!}
    </p>

    <p>
        <button class="btn btn-danger" type="button" data-toggle="collapse" href="#email-list-collapse" role="button" aria-expanded="false">
            @lang('projects.notifications.email_collapse')
        </button> 
        <button class="btn btn-danger" type="button" data-toggle="collapse" href="#telegram-list-collapse" role="button" aria-expanded="false">
            @lang('projects.notifications.telegram_collapse')
        </button> 
    </p>
    <div class="collapse multi-collapse" id="email-list-collapse">
        <div class="form-group col-md-12">
            <p class="text-white"><b>@lang('projects.notifications.emails_header')</b></p>
        </div>
        
        <div class="form-row">
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
        <div class="table-responsive">
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
    <div class="collapse multi-collapse" id="telegram-list-collapse">
        <p class="text-white"><strong>АДРЕСА TELEGRAM</strong></p>
        <p class="text-white"><strong>В РАБОТЕ</strong></p>
    </div>
</div>

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

@endsection

