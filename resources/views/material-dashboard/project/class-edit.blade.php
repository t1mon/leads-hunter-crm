@extends('material-dashboard.layouts.app')

@section('content')
@include('material-dashboard.project._classes-form',
[
    'title' => trans('projects.classes.create-new'),
    'route' => 'class.update',
    'method' => 'PUT',
])
@endsection