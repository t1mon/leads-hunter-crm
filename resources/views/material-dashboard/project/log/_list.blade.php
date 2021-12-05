@if($subcollection->count())
    @foreach ($subcollection as $entry)
        @switch($entry->action)
            @case(\App\Journal\Journal::ACTION_LOG)
                @include('material-dashboard.project.log._card-log')
                @break
            @case(\App\Journal\Journal::ACTION_PROJECT)
                @include('material-dashboard.project.log._card-project')
                @break
            @case(\App\Journal\Journal::ACTION_LEAD)
                @include('material-dashboard.project.log._card-lead')        
                @break
            @default
                @include('material-dashboard.project.log._card-log')
                @break
        @endswitch
    @endforeach
@else
    <div class="card my-3">
        <div class="card-body">
            <p class="card-text">@lang('projects.log.none')</p>
        </div>
    </div>
@endif