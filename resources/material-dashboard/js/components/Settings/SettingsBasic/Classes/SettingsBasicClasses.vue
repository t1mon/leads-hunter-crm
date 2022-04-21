<template>
    <div>

        <div class="card my-3">
            <div class="card-body">
<!--                <h6 class="card-title">{{$title}}</h6>-->
<!--                {!! Form::open(['route' => [$route, $project, $class], 'method' => $method ?? 'POST' ]) !!}-->
<!--                <p class="card-text">-->
<!--                    {!! Form::text('name', is_null($class) ? null : $class->name, ['class' => 'form-control', 'placeholder' => trans('projects.classes.name')]) !!}-->
<!--                </p>-->

<!--                <p class="card-text">-->
<!--                    {!! Form::text('color', is_null($class) ? null : $class->color, ['class' => 'form-control', 'placeholder' => trans('projects.classes.color')]) !!}-->
<!--                </p>-->

<!--                <p class="card-text">-->
<!--                    {!! Form::submit(-->
<!--                    trans('projects.button-' . (is_null($class) ? 'add' : 'change') ),-->
<!--                    ['class' => 'btn btn-primary',])-->
<!--                    !!}-->
<!--                </p>-->

<!--                {!! Form::close() !!}-->
            </div>
        </div>

<!--        Форма добавления нового класса-->
<!--        @include('material-dashboard.project._classes-form',-->
<!--        [-->
<!--        'title' => trans('projects.classes.create-new'),-->
<!--        'class' => null,-->
<!--        'route' => 'class.store',-->
<!--        ])-->
        <div class="card-group">
<!--            @if($project->classes->isEmpty())-->
<!--            <div class="card">-->
<!--                <div class="card-body">-->
<!--                    <div class="d-flex align-items-center justify-content-center">-->
<!--                        <h5 class="card-header"><b>@lang('projects.classes.table.none')</b></h5>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            @else-->
<!--            <div class="row row-cols-auto row-cols-md-auto g-3">-->
<!--                @foreach($project->classes as $class)-->
<!--                <div class="col">-->
<!--                    <div class="card">-->
<!--                        <div class="card-img-top p-3" style="background-color:#{{$class->color}}">-->
<!--                        </div>-->

<!--                        <div class="card-body d-flex align-items-center justify-content-center">-->
<!--                            <h6 class="card-title">{{$class->name}}</h6>-->
<!--                        </div>-->

<!--                        <div class="card-footer">-->
<!--                            {!! Form::open([-->
<!--                            'method' => 'DELETE',-->
<!--                            'route' => ['class.destroy', [$project, $class] ],-->
<!--                            'id' => $loop->index . '_delete',-->
<!--                            ]) !!}-->
<!--                            {!! Form::close() !!}-->

<!--                            <div class="btn-group-vertical d-flex align-items-center justify-content-center">-->
<!--                                <a href="{{route('class.edit', [$project, $class])}}" class="btn btn-secondary">-->
<!--                                    @lang('projects.button-change')-->
<!--                                </a>-->
<!--                                {!! Form::submit(-->
<!--                                trans('projects.button-delete'),-->
<!--                                [-->
<!--                                'class' => 'btn btn-danger',-->
<!--                                'form' => $loop->index . '_delete',-->
<!--                                ]-->
<!--                                ) !!}-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                @endforeach-->
<!--            </div>-->
<!--            @endif-->
        </div>
    </div>
</template>

<script>
export default {
    name: "SettingsBasicClasses"
}
</script>

<style scoped>

</style>
