@extends('material-dashboard.layouts.app')

@section('content')
    @include('material-dashboard.project.webhooks._form', ['type' => $type])
@endsection