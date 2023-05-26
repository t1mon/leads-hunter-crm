<div class="col-3">
    {{--Кнопка включения-выключения--}}
    <div class="card my-3">
        <div class="card-body">
            @if ($project->settings['enabled'])
                <h5 class="card-title text-center text-success">Проект включен</h5>
            @else
                <h5 class="card-title text-center text-secondary">Проект отключен</h5>
            @endif
            {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}
                {!! Form::hidden('settings[enabled]', $project->settings['enabled'] ? 0 : 1) !!}
                <div class="text-center">
                    <button class="btn btn-{{$project->settings['enabled'] ? 'danger' : 'secondary'}} btn-lg">
                        <i class="fa fa-power-off" aria-hidden="true"></i>
                    </button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>