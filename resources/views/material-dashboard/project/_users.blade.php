{{--Объявление полей журнала (чтобы быстро добавлять/удалять в дальнейшем--}}
@php
    $journal_fields = ['email', 'city', 'host'];
@endphp

{{--Форма для добавления нового пользователя--}}
<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title">@lang('projects.users.add-form.title')</h5>

        {!! Form::open(['route' =>  ['user.store', $project], 'class' => 'd-flex']) !!}
        <div>
            {!! Form::hidden('project_id', $project->id) !!}
            <input type="text" name="email" class="form-control" value="{{old('email')}}" placeholder="{{ trans('projects.users.add-form.placeholder') }}">

            <label>
                {!! Form::hidden('role', \App\Models\Role::ROLE_WATCHER) !!}
                <input type="checkbox" name="role" value="{{ \App\Models\Role::ROLE_MANAGER }}" >
                @lang('projects.users.add-form.make_admin')
            </label>

            <p class="card-text"><b>@lang('projects.users.add-form.view_fields'):</b></p>

            @foreach($journal_fields as $field)
                <p class="card-text ms-3">
                    <label>
                        <input type="checkbox" name="'view_fields[]" value="{{ $field }}" checked >
                        @lang('projects.journal.' . $field)
                    </label>
                </p>
            @endforeach

            {!! Form::button(trans('projects.button-add'), ['class' => 'btn btn-danger btn-sm', 'type' => 'submit']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>

{{--Таблица с пользователями--}}
<div class="card my-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('projects.users.table.user')</th>
                        <th colspan="2">
                            <i class="fa fa-flag" aria-hidden="true" data-toggle="tooltip" title="{{trans('projects.users.hints.make_admin')}}"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                            <i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" title="{{trans('projects.users.hints.view_fields')}}"></i>&nbsp;&nbsp;&nbsp;&nbsp;
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

                                {!! Form::hidden('role', \App\Models\Role::ROLE_WATCHER) !!}
                                {!! Form::checkbox(
                                    'role', "manager",
                                    $permission->role === \App\Models\Role::ROLE_MANAGER ? true : false,
                                    [( (Auth::user()->isWatcher($project) or $project->isOwner()) and $permission->user->id != $project->user->id) ? '' : 'disabled']
                                    )
                                !!}</label>&nbsp;&nbsp;&nbsp;&nbsp;

                                @foreach($journal_fields as $field)
                                <label>
                                    @lang('projects.journal.' . $field)
                                    {!! Form::checkbox(
                                        'view_fields[]', $field,
                                        in_array('email', $permission->view_fields) ? true : false,
                                        [( (Auth::user()->isWatcher($project) or $project->isOwner()) and $permission->user->id != $project->user->id) ? '' : 'disabled']
                                        )
                                    !!}
                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                @endforeach

                                {!! Form::submit(trans('projects.button-save'),
                                    [
                                        'class' => 'btn btn-danger btn-sm',
                                        ( (Auth::user()->isWatcher($project) or $project->isOwner()) and $permission->user->id != $project->user->id) ? '' : 'disabled'
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
                                        ( (Auth::user()->isWatcher($project) or $project->isOwner()) and $permission->user->id != $project->user->id) ? '' : 'disabled'
                                    ]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
