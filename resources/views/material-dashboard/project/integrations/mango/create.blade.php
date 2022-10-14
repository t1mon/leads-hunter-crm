@extends('material-dashboard.layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="card">
                {!! Form::open(['url' => route('project.integrations.mango.store'), 'method' => 'POST']) !!}

                <h5 class="card-header text-center text-uppercase">Новая интеграция с Mango Office</h5>
                
                <div class="card-body">
                    <div class="container-fluid">
                        {!! Form::hidden('project_id', $project->id) !!}
                        @include('material-dashboard.project.integrations.mango.form')
                    </div>
                </div>

                <div class="card-footer">
                        <div class="row justify-content-center">
                            <div class="col-auto text-center">
                                {!! Form::submit('Добавить', ['class' => 'btn btn-primary']) !!}
                            </div>

                            <div class="col-auto text-center">
                                <a href="{{route('project.integrations.mango.index', $project->id)}}" class="btn btn-info">
                                    Назад
                                </a>
                            </div>
                        </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection