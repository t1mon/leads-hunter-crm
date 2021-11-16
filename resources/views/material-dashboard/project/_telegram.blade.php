@php
    $telegram_fields = ['email', 'city', 'host'];
@endphp

{{--Скрытая форма для добавления группового чата--}}
{!! Form::open( ['route' => ['telegram.store', $project], 'id' => 'group_id_store',] ) !!}
{!! Form::hidden('type', \App\Models\Project\TelegramID::TYPE_CHANNEL) !!}
{!! Form::close() !!}

{{--Скрытая форма для отвязки группового чата--}}
@if(!is_null($telegram_groupID))
    {!! Form::model(
        $telegram_groupID,
        [
            'id' => 'group_id_delete',
            'route' => ['telegram.destroy', $project, $telegram_groupID->id],
            'method' => 'DELETE'
        ]) !!}
    {!! Form::close() !!}
@endif

{{--Скрытая форма добавления личного контакта--}}
{!! Form::open( ['route' => ['telegram.store', $project], 'id' => 'private_id_store',] ) !!}
{!! Form::hidden('type', \App\Models\Project\TelegramID::TYPE_PRIVATE) !!}
{!! Form::close() !!}

{{--Модалка для добавления группового чата--}}
@include('material-dashboard.project._telegram-modal', 
        [
            'modal_id' => 'modal_group',
            'form_id' => 'group_id_store',
            'title' => trans('projects.notifications.telegram.form_add_group'),
            'contact' => $telegram_groupID,
        ])

{{--Модалка для добавления личных контактов--}}
@include('material-dashboard.project._telegram-modal', 
        [
            'modal_id' => 'modal_private',
            'form_id' => 'private_id_store',
            'title' => trans('projects.notifications.telegram.form_add_private'),
            'contact' => null,
        ])

{{--Таблица общих настроек--}}
<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title">@lang('projects.notifications.telegram.general_settings')</h5>
        {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}
        <p class="card-text">
            <div class="form-check form-switch ps-2">
                {!! Form::hidden('settings[telegram][enabled]', 0) !!}
                {!! Form::checkbox(
                    'settings[telegram][enabled]',
                    1,
                    $project->settings['telegram']['enabled'] ? true : false,
                    [
                        'id' => 'telegram_toggle',
                        'class' => 'form-check-input ms-auto',
                    ]
                ) !!}
            </div>
            
            <label class="ms-3" for="telegram_toggle">
                @lang('projects.notifications.telegram.toggle')
            </label>
        </p>

        <h6 class="card-title">@lang('projects.notifications.telegram.fields'):</h5>
        @foreach($telegram_fields as $field)
            <p class="card-text ms-5">
                    {!! Form::checkbox(
                        'settings[telegram][fields][]', 
                        $field, 
                        in_array($field, $project->settings['telegram']['fields']) ? true : false) 
                    !!}
                    @lang('projects.journal.' . $field)
            </p>
        @endforeach

        <p class="card-text ms-5">
            {!! Form::submit(trans('projects.button-save'), ['class' => 'btn btn-primary',]) !!}
        </p>

        {!! Form::close() !!}
    </div>
</div>


{{--Таблица для группового чата--}}
<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title">@lang('projects.notifications.telegram.group')</h5>
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <tbody>
                    <tr>
                        <td>
                        <b>{{$telegram_groupID->name ?? trans('projects.notifications.telegram.group_none')}}</b>
                        </td>
                        
                        <td>
                            @if(!is_null($telegram_groupID))
                                {{ trans('projects.notifications.telegram.' . ($telegram_groupID->approved ? 'approved' : 'not_approved') )}}
                            @endisset
                        </td>

                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_group">
                                @isset($telegram_groupID)
                                    @lang('projects.button-change')
                                @else                                
                                    @lang('projects.button-add')
                                @endempty
                            </button>
                        </td>
                        
                        <td>
                            {!! Form::submit( 
                                trans('projects.button-delete'), 
                                [
                                    'class' => 'btn btn-danger',
                                    'form' => 'group_id_delete',
                                    is_null($telegram_groupID) ? 'disabled' : '',
                                ]) 
                            !!}                        
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{--Таблица для личных контактов--}}
<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title">@lang('projects.notifications.telegram.private')</h5>
        <p class="card-text">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_private">
                @lang('projects.button-add')
            </button>
        </p>
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('projects.notifications.telegram.username')</th>
                        <th>@lang('projects.notifications.telegram.user_id')</th>
                        <th>@lang('projects.notifications.telegram.status')</th>
                        <th>@lang('projects.notifications.telegram.actions')</th>
                    </tr>
                </thead>

                <tbody>
                    @if($telegram_privateIDs->isEmpty())
                        <tr>
                            <td colspan="3">@lang('projects.notifications.telegram.private_none')</td>
                        </tr>
                    @endempty

                    @foreach($telegram_privateIDs as $id)
                        <tr>
                            <td>{{$id->id}}</td>
                            <td>{{$id->name}}</td>
                            <td>{{$id->number ?? trans('projects.not_specified')}}</td>
                            <td>{{ trans('projects.notifications.telegram.' . ($id->approved ? 'approved' : 'not_approved') )}}</td>
                            <td>
                                {!! Form::model(
                                    $id,
                                    [
                                        'route' => ['telegram.destroy', $project, $id->id],
                                        'method' => 'DELETE'
                                    ]) !!}

                                    {!! Form::button(
                                        '<i class="fa fa-trash" aria-hidden="true"></i>',
                                        [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger'
                                        ]
                                    ) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
