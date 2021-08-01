@extends('layouts.app')

@section('content')
    <h1>Создание проекта</h1>

    {!! Form::open(['route' => ['project.store'], 'method' =>'POST']) !!}

    @include('project/_form')

    {{ link_to_route('project.index', __('forms.actions.back'), [], ['class' => 'btn btn-secondary']) }}
    {!! Form::submit(__('forms.actions.save'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection
