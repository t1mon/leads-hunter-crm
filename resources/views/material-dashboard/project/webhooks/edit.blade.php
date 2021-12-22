@extends('material-dashboard.layouts.app')

@section('content')
    
    {{-- @includeWhen($type === \App\Models\Project\Project::WEBHOOK_BITRIX24, 'material-dashboard.project.webhooks._form-bitrix24')
    @includeWhen($type === \App\Models\Project\Project::WEBHOOK_COMMON, 'material-dashboard.project.webhooks._form', ['type' =>  \App\Models\Project\Project::WEBHOOK_COMMON]) --}}

    @include('material-dashboard.project.webhooks._form-new')

    
@endsection