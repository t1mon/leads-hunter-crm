{{--Форма добавления нового класса--}}
@include('material-dashboard.project._classes-form',
    [
        'title' => trans('projects.classes.create-new'),
        'class' => null,
        'route' => 'class.store',
    ])

{{--Список классов--}}
<div class="card my-3">
    <div class="card-body">
        <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID #</th>
                            <th>@lang('projects.classes.table.name')</th>
                            <th>@lang('projects.classes.table.color')</th>
                            <th colspan="2">@lang('projects.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($project->classes->isEmpty())
                            <tr>
                                <td colspan="5"><b>@lang('projects.classes.table.none')</b></td>
                            </tr>
                        @else
                            @foreach($project->classes as $class)
                                <tr class="align-middle">
                                    <td>{{$class->id}}</td>
                                    <td><b>{{$class->name}}</b></td>
                                    <td style="background-color: #{{$class->color}};text-align:center" class="text-white">
                                        <b>#{{$class->color}}</b>
                                    </td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('class.edit', [$project, $class])}}">
                                            @lang('projects.button-change')
                                        </a>
                                    </td>
                                    <td>
                                        {!! Form::model($class, ['method' => 'DELETE', 'route' => [ 'class.destroy', [$project, $class] ] ]) !!}
                                            {!! Form::submit(trans('projects.button-delete'), ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
        </div>
    </div>
</div>