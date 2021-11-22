<div class="card my-3">
    <div class="card-body">
        <h6 class="card-title">{{$title}}</h6>
        {!! Form::open(['route' => [$route, $project, $class], 'method' => $method ?? 'POST' ]) !!}
        <p class="card-text">
            {!! Form::text('name', is_null($class) ? null : $class->name, ['class' => 'form-control', 'placeholder' => trans('projects.classes.name')]) !!}
        </p>

        <p class="card-text">
            {!! Form::text('color', is_null($class) ? null : $class->color, ['class' => 'form-control', 'placeholder' => trans('projects.classes.color')]) !!}
        </p>

        <p class="card-text">
            {!! Form::submit( 
                trans('projects.button-' . (is_null($class) ? 'add' : 'change') ), 
                ['class' => 'btn btn-primary',])
            !!}
        </p>

        {!! Form::close() !!}
    </div>
</div>