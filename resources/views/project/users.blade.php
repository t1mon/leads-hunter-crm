@extends('layouts.project')

@section('content')
    <!--Форма для добавления нового пользователя-->
    <div>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#add-form" aria-expanded="false" aria-controls="add-form">
            @lang('projects.users.add-form.button')
        </button>

        <div class="collapse" id="add-form">
            <div class="card card-body">
                {!! Form::open(['route' =>  ['user.store', $project], 'class' => 'd-flex']) !!}
            <div>
                {!! Form::hidden('project_id', $project->id) !!}
                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Введите email']) !!}
                <label>
                    {!! Form::hidden('admin', 0) !!}
                    {!! Form::checkbox('admin', 1) !!}
                    @lang('projects.users.add-form.make_admin')
                </label>

                <ul>
                    <li>
                        <label>
                            {!! Form::hidden('manage_users', 0) !!}
                            {!! Form::checkbox('manage_users', 1) !!}
                            @lang('projects.users.add-form.manage_users')
                        </label>
                    </li>

                    <li>
                        <label>
                            {!! Form::hidden('manage_settings', 0) !!}
                            {!! Form::checkbox('manage_settings', 1) !!}
                            @lang('projects.users.add-form.manage_settings')
                        </label>
                    </li>

                    <li>
                        <label>
                            {!! Form::hidden('manage_payments', 0) !!}
                            {!! Form::checkbox('manage_payments', 1) !!}
                            @lang('projects.users.add-form.manage_payments')
                        </label>
                    </li>

                    <li>
                        <label>
                            {!! Form::hidden('view_journal', 0) !!}
                            {!! Form::checkbox('view_journal', 1, true) !!}
                            @lang('projects.users.add-form.view_journal')
                        </label>
                    </li>

                    <li>
                        @lang('projects.journal.email')
                        {!! Form::checkbox('view_fields[]', 'email', true) !!}
                    </li>

                    <li>
                        @lang('projects.journal.city')
                        {!! Form::checkbox('view_fields[]', 'city', true) !!}
                    </li>

                    <li>
                        @lang('projects.journal.host')
                        {!! Form::checkbox('view_fields[]', 'host', false) !!}
                    </li>

                    <li>
                        UTM
                        {!! Form::checkbox('view_fields[]', 'utm', false) !!}
                    </li>
                </ul>
                {!! Form::button('Добавить', ['class' => 'btn btn-danger btn-sm', 'type' => 'submit']) !!}
            </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!--Список существующих пользователей-->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-dark ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('projects.users.table.user')</th>
                    <th colspan="2">
                        <i class="fa fa-flag" aria-hidden="true" data-toggle="tooltip" title='Сделать администратором'></i>&nbsp;&nbsp;&nbsp;&nbsp;
                        {{--
                        <i class="fa fa-user" aria-hidden="true" data-toggle="tooltip" title='Разрешить добавлять и удалять пользователей в проект'></i>&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-gear" aria-hidden="true" data-toggle="tooltip" title='Разрешить управление настройками проекта'></i>&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-money" aria-hidden="true" data-toggle="tooltip" title='Разрешить осуществлять оплату'></i>&nbsp;&nbsp;&nbsp;&nbsp;
                        --}}
                        <i class="fa fa-laptop" aria-hidden="true" data-toggle="tooltip" title='Разрешить просмотр журнала'></i>&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" title='Поля, которые будут отображаться для пользователя'></i>&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                @if(count($project->user_permissions) < 1)
                    <tr>
                        <td colspan="11">@lang('projects.users.table.none')</td>
                    </tr>
                @endif

                @foreach($permissions as $permission)
                    <tr>
                        <td>{{$permission->id}}</td>
                        
                        <td>
                            {{$permission->user->email}}
                            @if($permission->user_id == $project->user_id)
                                <b> (@lang('projects.users.table.creator'))</b>
                            @endif
                        </td>
                        
                        <td>
                        {!! Form::model($permission, ['route' => ['user.update', $project, $permission], 'method' => 'PUT']) !!}
                            
                                {!! Form::hidden('role', $permission->role::ROLE_WATCHER) !!}
                                {!! Form::checkbox(
                                    'role', $permission->role::ROLE_MANAGER,
                                    $permission->role == $permission->role::ROLE_MANAGER ? true : false,
                                    [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                    ) 
                                !!}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                            {{--
                            {!! Form::hidden('manage_users', 0) !!}
                            {!! Form::checkbox(
                                'manage_users', true,
                                $permission->manage_users ? true : false,
                                [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                ) 
                            !!}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    
                            {!! Form::hidden('manage_settings', 0) !!}
                            {!! Form::checkbox(
                                'manage_settings', true,
                                $permission->manage_settings ? true : false,
                                [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                ) 
                            !!}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        
                            {!! Form::hidden('manage_payments', 0) !!}
                            {!! Form::checkbox(
                                'manage_payments', true,
                                $permission->manage_payments ? true : false,
                                [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                ) 
                            !!}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                            --}}
                        
                            {!! Form::hidden('view_journal', 0) !!}
                            {!! Form::checkbox(
                                'view_journal', true,
                                $permission->view_journal ? true : false,
                                [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                ) 
                            !!}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        
                            <label>
                                @lang('projects.journal.email')
                                {!! Form::checkbox(
                                    'view_fields[]', 'email',
                                    in_array('email', $permission->view_fields) ? true : false,
                                    [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                    ) 
                                !!}
                            </label>&nbsp;&nbsp;&nbsp;&nbsp;

                            <label>
                                @lang('projects.journal.city')
                                {!! Form::checkbox(
                                    'view_fields[]', 'city',
                                    in_array('city', $permission->view_fields) ? true : false,
                                    [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                    ) 
                                !!}
                            </label>&nbsp;&nbsp;&nbsp;&nbsp;

                            <label>
                                @lang('projects.journal.host')
                                {!! Form::checkbox(
                                    'view_fields[]', 'host',
                                    in_array('host', $permission->view_fields) ? true : false,
                                    [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                    ) 
                                !!}
                            </label>&nbsp;&nbsp;&nbsp;&nbsp;

                            <label>
                                UTM
                                {!! Form::checkbox(
                                    'view_fields[]', 'utm',
                                    in_array('utm', $permission->view_fields) ? true : false,
                                    [(Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled']
                                    ) 
                                !!}
                            </label>&nbsp;&nbsp;&nbsp;&nbsp;

                            {!! Form::button('Сохранить',
                                [
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    (Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled'
                                ])
                            !!}
                        </td>
                        {!! Form::close()!!}
                        
                        <td>
                            {!! Form::model($permission, [ 'method' => 'DELETE', 'route' => ['user.destroy', $project, $permission] ]) !!}
                            {!! Form::button(
                                '<i class="fa fa-trash" aria-hidden="true"></i>',
                                [
                                    'class' => 'btn btn-danger btn-sm',
                                    'type' => 'submit',
                                    'data-confirm' => __('forms.user-permissions.delete'),
                                    (Auth::user()->isManagerFor($project) and $permission->user_id != $project->id ) ? '' : 'disabled'
                                ]) !!}
                            {!! Form::close() !!}

                            
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection

