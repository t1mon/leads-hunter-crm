{{--Объявление полей журнала (чтобы быстро добавлять/удалять в дальнейшем--}}
@php
    $mailing_fields = ['email', 'city', 'host'];
@endphp

<div class="container">
    <div class="row row-cols-2">
        {{--Форма настроек e-mail--}}
        <div class='col'>
            <div class="card my-3">
                <div class="card-body">
                    {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', [$project]] ]) !!}
                        <div class="border rounded-3 border-primary my-3 p-2">
                            <h5 class="card-title text-center">@lang('projects.notifications.emails_settings')</h5>
                            <div class="my-1">
                                {!! Form::hidden('settings[email][enabled]', 0) !!}
                                {!! Form::checkbox('settings[email][enabled]', 1, $project->settings['email']['enabled'] ? true : false, ['id' => 'enabled_checkbox']) !!}
                                {!! Form::label('enabled_checkbox', trans('projects.notifications.emails_toggle'), ['class' => 'form-label']) !!}
                            </div>
                            <div class="my-1">
                                {!! Form::hidden('settings[email][send_all]', 0) !!}
                                {!! Form::checkbox('settings[email][send_all]', 1, $project->settings['email']['send_all'] ? true : false, ['id' => 'send_all_checkbox']) !!}
                                {!! Form::label('send_all_checkbox', trans('projects.notifications.emails_send_all'), ['class' => 'form-label']) !!}
                            </div>
                            <div class="my-2">
                                {!! Form::label('email-subject', trans('projects.notifications.emails_subject') . ':', ['class' => 'form-label']) !!}
                                {!! Form::text('settings[email][subject]', $project->settings['email']['subject'], ['class' => 'form-control border p-2 w-50', 'placeholder' => trans('projects.notifications.emails_subject'), 'id' => 'email-subject']) !!}
                            </div>
                        </div>
                        
                        <div class="border rounded-3 border-primary my-3 p-2">
                        <h6 class="card-title text-center">@lang('projects.notifications.emails_fields')</h6>
                            @foreach($mailing_fields as $field)
                                <p class="card-text ms-3">
                                    {!! Form::checkbox('settings[email][fields][]', $field, in_array($field, $project->settings['email']['fields']) ? true : false) !!} @lang('projects.journal.' . $field)
                                </p>
                            @endforeach
                        </div>
                        
                        <div class="my-3 text-center">
                            {!! Form::button(trans('projects.notifications.emails_save'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
                        </div>
                    {!! Form::close()!!}
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