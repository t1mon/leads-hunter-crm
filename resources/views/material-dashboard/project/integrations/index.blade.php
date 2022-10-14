@extends('material-dashboard.layouts.app')

@section('content')

<div>
    <ul class="nav nav-tabs">
        {{--VK--}}
        <li class="nav-item">
            <a  class="nav-link active" data-bs-toggle="tab" href="#vk">
                ВКонтакте
            </a>
        </li>

        {{--REST API--}}
        <li class="nav-item">
            <a  class="nav-link" data-bs-toggle="tab" href="#restapi">
                REST API
            </a>
        </li>

        {{-- Mango Office --}}
        <li class="nav-item">
            <a  class="nav-link" data-bs-toggle="tab" href="#mango">
                Mango Office
            </a>
        </li>
    </ul>
</div>

{{--Содержимое вкладок--}}
<div class="tab-content">
    {{--VK--}}
    <div class="tab-pane fade show active" id="vk" role="tabpanel">
        @include('material-dashboard.project.integrations.vk.index')
    </div>

    {{--REST API--}}
    <div class="tab-pane fade show" id="restapi" role="tabpanel">
        @include('material-dashboard.project.integrations.restapi')
    </div>

    {{--Mango Office--}}
    <div class="tab-pane fade show" id="mango" role="tabpanel">
        <a href="{{route('project.integrations.mango.index', $project->id)}}" class="link-primary">Интеграции с Mango Office</a>
    </div>
</div>

@endsection