@extends('layouts.project')

@section('content')
    <!--Форма для добавления нового пользователя-->
    
    {!! Form::open(['route' =>  ['user.store', $project], 'class' => 'd-flex']) !!}
    <div>
        {!! Form::hidden('project_id', $project->id) !!}
        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Введите email']) !!}
        <label>
            {!! Form::hidden('admin', false) !!}
            {!! Form::checkbox('admin', true) !!}
            Сделать администратором
        </label>

        <ul>
            <li>
                <label>
                    {!! Form::hidden('manage_users', false) !!}
                    {!! Form::checkbox('manage_users', true) !!}
                    Управление пользователями
                </label>
            </li>

            <li>
                <label>
                    {!! Form::hidden('manage_settings', false) !!}
                    {!! Form::checkbox('manage_settings', true) !!}
                    Управление настройками
                </label>
            </li>

            <li>
                <label>
                    {!! Form::hidden('manage_payments', false) !!}
                    {!! Form::checkbox('manage_payments', true) !!}
                    Управление платежами
                </label>
            </li>

            <li>
                <label>
                    {!! Form::hidden('view_journal', false) !!}
                    {!! Form::checkbox('view_journal', true) !!}
                    Просмотр журнала
                </label>
            </li>

            <li>
                E-mail
                {!! Form::checkbox('view_fields[$]', 'email', false) !!}
            </li>

            <li>
                Город
                {!! Form::checkbox('view_fields[]', 'city', false) !!}
            </li>

            <li>
                Посадочная
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

    <!--Список существующих пользователей-->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-dark ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('projects.users.user')</th>
                    <th><i class="fa fa-flag" aria-hidden="true" data-toggle="tooltip" title="Сделать администратором"></i></th>
                    <th><i class="fa fa-user" aria-hidden="true" data-toggle="tooltip" title="Разрешить добавлять и удалять пользователей в проект"></i></th>
                    <th><i class="fa fa-gear" aria-hidden="true" data-toggle="tooltip" title="Разрешить управление настройками проекта"></i></th>
                    <th><i class="fa fa-money" aria-hidden="true" data-toggle="tooltip" title="Разрешить осуществлять оплату"></i></th>
                    <th><i class="fa fa-laptop" aria-hidden="true" data-toggle="tooltip" title="Разрешить просмотр журнала"></th>
                    <th colspan="5"><i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" title="Поля, которые будут отображаться для пользователя"></th>
                </tr>
            </thead>
            <tbody>
                @if(count($project->user_permissions) < 1)
                    <tr>
                        <td colspan="11">На проект не назначено пользователей</td>
                    </tr>
                @endif

                @foreach($permissions as $permission)
                    <tr>
                        <td>{{$permission->id}}</td>
                        
                        <td>{{$permission->user->email}}</td>
                        
                        <td>
                            @if($project->isOwner())
                                <b>Создатель</b>
                            @else
                                {!! Form::model($permission, ['method' => 'PUT',]) !!}
                                    {!! Form::hidden('role_id', $permission->user->isAdmin($project) ? $permission->role::ROLE_WATCHER_ID : $permission->role::ROLE_ADMIN_ID) !!}
                                    {!! Form::button('<i class="fa ' . ($permission->user->isAdmin($project) ? 'fa-times' : 'fa-plus') . '" aria-hidden="true"></i>', 
                                                    ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
                                {!! Form::close() !!}
                            @endif
                        </td>
                        
                        {!! Form::model($permission, ['method' => 'PUT']) !!}
                        <td>
                            {!! Form::hidden('manage_users', false) !!}
                            {!! Form::checkbox(
                                'manage_users', true,
                                $permission->manage_users ? true : false,
                                [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                ) 
                            !!}
                        </td>

                        <td>
                            {!! Form::hidden('manage_settings', false) !!}
                            {!! Form::checkbox(
                                'manage_settings', true,
                                $permission->manage_settings ? true : false,
                                [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                ) 
                            !!}
                        </td>

                        <td>
                            {!! Form::hidden('manage_payments', false) !!}
                            {!! Form::checkbox(
                                'manage_payments', true,
                                $permission->manage_payments ? true : false,
                                [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                ) 
                            !!}
                        </td>

                        <td>
                            {!! Form::hidden('view_journal', false) !!}
                            {!! Form::checkbox(
                                'view_journal', true,
                                $permission->view_journal ? true : false,
                                [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                ) 
                            !!}
                        </td>

                        <td>
                            <label>
                                E-mail
                                {!! Form::checkbox(
                                    'view_fields[]', 'email',
                                    in_array('email', $permission->view_fields) ? true : false,
                                    [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                    ) 
                                !!}
                            </label>

                            <label>
                                Город
                                {!! Form::checkbox(
                                    'view_fields[]', 'city',
                                    in_array('city', $permission->view_fields) ? true : false,
                                    [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                    ) 
                                !!}
                            </label>

                            <label>
                                Посадочная
                                {!! Form::checkbox(
                                    'view_fields[]', 'host',
                                    in_array('host', $permission->view_fields) ? true : false,
                                    [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                    ) 
                                !!}
                            </label>

                            <label>
                                UTM
                                {!! Form::checkbox(
                                    'view_fields[]', 'utm',
                                    in_array('utm', $permission->view_fields) ? true : false,
                                    [($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : '']
                                    ) 
                                !!}
                            </label>

                            {!! Form::button('Сохранить',
                                [
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    ($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : ''
                                ])
                            !!}
                        </td>
                        {!! Form::close()!!}
                        
                        <td>
                            {!! Form::model($permission, ['method' => 'DELETE', ]) !!}
                            {!! Form::button(
                                    '<i class="fa fa-trash" aria-hidden="true"></i>',
                                    [
                                        'class' => 'btn btn-danger btn-sm',
                                        'type' => 'submit',
                                        ($permission->user->isAdmin($project) or $project->isOwner()) ? 'disabled' : ''
                                    ]) 
                            !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection

