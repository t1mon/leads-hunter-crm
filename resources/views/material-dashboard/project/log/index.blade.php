@extends('material-dashboard.layouts.app')

@section('content')
    {{--Ссылки на скачивание--}}
    <div class="card my-2">
        <div class="card-body text-center">
            <span class="me-6">
                Скачать записи за сегодня:
                <a href="{{route('project.log-export', ['project' => $project, 'method' => 'today', 'format' => \Maatwebsite\Excel\Excel::XLSX])}}" class="card-link">.xlsx</a>
                <span>|</span>
                <a href="{{route('project.log-export', ['project' => $project, 'method' => 'today', 'format' => \Maatwebsite\Excel\Excel::CSV])}}" class="card-link">.csv</a>
            </span>
            
            <span>
                Скачать все записи:
                <a href="{{route('project.log-export', ['project' => $project, 'method' => 'all', 'format' => \Maatwebsite\Excel\Excel::XLSX])}}" class="card-link">.xlsx</a>
                <span>|</span>
                <a href="{{route('project.log-export', ['project' => $project, 'method' => 'all', 'format' => \Maatwebsite\Excel\Excel::CSV])}}" class="card-link">.csv</a>
            </span>
            
        </div>
    </div>


    {{--Вкладки--}}
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#leads">
                @lang('projects.log.tabs.leads')
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#all">
                @lang('projects.log.tabs.all')
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#info">
                @lang('projects.log.tabs.info')
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#warnings">
                @lang('projects.log.tabs.warnings')
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#errors">
                @lang('projects.log.tabs.errors')
            </a>
        </li>
    </ul>

    {{--Содержимое вкладок--}}
    <div class="tab-content">
        <div class="tab-pane fade show active" id="leads" role="tabpanel">
            @include('material-dashboard.project.log._list', 
                    ['subcollection' => $entries->where('action', \App\Journal\Journal::ACTION_LEAD)])
        </div>

        <div class="tab-pane fade show" id="all" role="tabpanel">
            @include('material-dashboard.project.log._list', 
                    ['subcollection' => $entries])
        </div>

        <div class="tab-pane fade show" id="info" role="tabpanel">
            @include('material-dashboard.project.log._list', 
                    ['subcollection' => $entries->where('class', \App\Journal\Journal::CLASS_INFO)])
        </div>

        <div class="tab-pane fade show" id="warnings" role="tabpanel">
            @include('material-dashboard.project.log._list', 
                    ['subcollection' => $entries->where('class', \App\Journal\Journal::CLASS_WARNING)])
        </div>

        <div class="tab-pane fade show" id="errors" role="tabpanel">
            @include('material-dashboard.project.log._list', 
                    ['subcollection' => $entries->where('class', \App\Journal\Journal::CLASS_ERROR)])
        </div>
    </div>
    
    <div class="text-center my-3">
        <a href="{{route('project.log', ['project' => $project, 'amount' => 'all'])}}" class='link-primary'>@lang('projects.log.show-all')</a>
    </div>

@endsection