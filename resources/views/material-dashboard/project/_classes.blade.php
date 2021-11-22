{{--Форма добавления нового класса--}}
@include('material-dashboard.project._classes-form',
    [
        'title' => trans('projects.classes.create-new'),
        'class' => null,
        'route' => 'class.store',
    ])

{{--Список классов--}}
{{-- <div class="card my-3">
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
</div> --}}

<div class="card-group">
    @if($project->classes->isEmpty())
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center">
                    <h5 class="card-header"><b>@lang('projects.classes.table.none')</b></h5>
                </div>
            </div>
        </div>    
    @else
        <div class="row row-cols-auto row-cols-md-auto g-3">
            @foreach($project->classes as $class)
                <div class="col">
                    <div class="card">
                        <div class="card-img-top p-3" style="background-color:#{{$class->color}}">
                        </div>
                        
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <h6 class="card-title">{{$class->name}}</h6>
                        </div>

                        <div class="card-footer">
                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['class.destroy', [$project, $class] ],
                                'id' => $loop->index . '_delete',
                            ]) !!}
                            {!! Form::close() !!}

                            <div class="btn-group-vertical d-flex align-items-center justify-content-center">
                                <a href="{{route('class.edit', [$project, $class])}}" class="btn btn-secondary">
                                    @lang('projects.button-change')
                                </a>
                                {!! Form::submit(
                                        trans('projects.button-delete'),
                                        [
                                            'class' => 'btn btn-danger',
                                            'form' => $loop->index . '_delete',
                                        ]
                                ) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>