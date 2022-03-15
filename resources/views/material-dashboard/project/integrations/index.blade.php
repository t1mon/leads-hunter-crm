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
</div>

@endsection