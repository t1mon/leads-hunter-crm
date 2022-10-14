@extends('material-dashboard.layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-8">

        <div class="card">
            <h5 class="card-header text-center">{{$integration->name}}</h5>

            <div class="card-body">
                    <div class="row my-2">
                        <div class="col-sm-5">
                            Проект:
                        </div>

                        <div class="col-sm-7 fw-bold">
                            <a href="{{route('project.journal', $project->id)}}">{{$project->name}}</a>
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-sm-5">
                            Уникальный код АТС:
                        </div>

                        <div class="col-sm-7 fw-bold">
                            {{$integration->vpbx_api_key}}
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-sm-5">
                            Ключ для создания подписи:
                        </div>

                        <div class="col-sm-7 fw-bold">
                            {{$integration->vpbx_api_salt}}
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-sm-5">
                            Статус:
                        </div>

                        <div class="col-sm-auto fw-bold">
                            @if ($integration->enabled)
                                <span class="text-success">Активна</span>
                            @else
                                <span class="text-secondary">Отключена</span>
                            @endif
                        </div>

                        <div class="col-sm-auto">
                            <a href="{{route('project.integrations.mango.toggle', $integration->id)}}" class="btn btn-sm btn-primary">
                                <i class="fa fa-power-off fs-6"></i>
                            </a>
                        </div>
                    </div>
            </div>

            <div class="card-footer">
                <div class="row justify-content-center">
                    <div class="col-auto text-center">
                        <a href="{{route('project.integrations.mango.edit', $integration->id)}}" class="btn btn-info">
                            Изменить
                        </a>
                    </div>

                    <div class="col-auto text-center">
                        {!! Form::model($integration, ['route' => ['project.integrations.mango.destroy', $integration->id], ]) !!}
                            {!! Form::submit('Удалить', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="row-sm justify-content-center">
                    <div class="col-auto text-center">
                        <a href="{{route('project.integrations.mango.index', $integration->project_id)}}" class="link-secondary" style="font-size: 0.8rem">
                            Вернуться к списку
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection