<div class="container">
    <div class="row row-cols-3">
        {{--Кнопка включения-выключения--}}
        <div class="col">
            <div class="card my-3">
                <div class="card-body">
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
        
        {{--Название проекта--}}
        <div class="col">
            <div class="card my-3">
                <div class="card-body">
                    <h5 class="card-title text-center">@lang('projects.properties.info')</h5>
                    {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}
                        <div class="mb-3">
                            {!! Form::label('project-name', trans('projects.properties.name'), ['class' => 'form-label']) !!}
                            {!! Form::text(
                                'properties[name]',
                                $project->name,
                                [
                                    'id' => 'project-name',
                                    'placeholder' => trans('projects.properties.name'),
                                    'class' => 'form-control border p-2'
                                ]
                            ) !!}
                        </div>
                        <div class="mb-3">
                            {!! Form::label('project-description', trans('projects.properties.description'), ['class' => 'form-label']) !!}
                            {!! Form::textarea(
                                'settings[description]',
                                $project->settings['description'],
                                [
                                    'id' => 'project-description',
                                    'placeholder' => trans('projects.properties.description'),
                                    'class' => 'form-control border p-2'
                                ]
                            ) !!}
                        </div>
                        <div class="mb-3 text-center">
                            {!! Form::submit(trans('projects.button-change'), ['class' => 'btn btn-primary'] ) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{--Часовой пояс--}}
        <div class="col">
            <div class="card my-3">
                <div class="card-body">
                    <h5 class="card-title">@lang('projects.properties.timezone')</h5>
                    {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}
                        @php
                            $timezones_raw = timezone_identifiers_list();
                            $timezones = [];
                            foreach($timezones_raw as $item)
                                $timezones[$item] = $item;
                        @endphp

                        <p class="card-text">
                            {!! Form::select('settings[timezone]', $timezones, $project->timezone) !!}
                        </p>

                        <p class="card-text">
                            {!! Form::submit(trans('projects.button-change'), ['class' => 'btn btn-primary'] ) !!}
                        </p>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- Срок годности лида --}}
        <div class="col">
            <div class="card my-3">
                <div class="card-body">
                    <h5 class="card-title">Срок годности лида</h5>
                    {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}
                        <p class="card-text">
                            {!! Form::label('leadValidDays', 'Кол-во дней', []) !!}
                            {!! Form::number('settings[leadValidDays]', $project->settings['leadValidDays'], ['id' => 'leadValidDays', 'class' => 'form-control']) !!}
                        </p>

                        <p class="card-text">
                            {!! Form::submit(trans('projects.button-change'), ['class' => 'btn btn-primary'] ) !!}
                        </p>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</div>