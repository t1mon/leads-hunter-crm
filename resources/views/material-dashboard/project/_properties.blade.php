{{--Название проекта--}}
<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title">@lang('projects.properties.name')</h5>
        {!! Form::model($project, ['method' => 'PUT', 'route' => ['project.update', $project] ]) !!}
            <p class="card-text">
                {!! Form::text(
                    'properties[name]',
                    $project->name,
                    [
                        'placeholder' => trans('projects.properties.name'),
                        'class' => 'form-control'
                    ]
                ) !!}
            </p>
            <p class="card-text">
                {!! Form::submit(trans('projects.button-change'), ['class' => 'btn btn-primary'] ) !!}
            </p>
        {!! Form::close() !!}
    </div>
</div>

{{--Часовой пояс--}}
{{-- Имя для поля ввода: 'settings[timezone]' --}}
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