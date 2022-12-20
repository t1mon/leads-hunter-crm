@extends('material-dashboard.layouts.app')

@section('content')
    <journal :projectid="{{ $projectId }}"></journal>
@endsection
