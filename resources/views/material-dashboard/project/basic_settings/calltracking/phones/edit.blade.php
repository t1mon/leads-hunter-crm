@extends('material-dashboard.layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-4">
            {!! Form::open(['url' => route('project.integrations.calltracking.phones.update', [$project->id, $phone->id]), 'method' => 'PUT']) !!}
                @include('material-dashboard.project.basic_settings.calltracking.phones._form')
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection