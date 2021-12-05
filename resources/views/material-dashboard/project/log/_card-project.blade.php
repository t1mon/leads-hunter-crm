<div class="card my-3">
    @switch($entry->class)
    @case('info')
        <div class="card-img-top bg-info p-2">
        <h6 class="card-title text-white">
        @break
    @case('warning')
        <div class="card-img-top bg-warning p-2">
        <h6 class="card-title text-dark">
        @break
    @case('error')
        <div class="card-img-top bg-danger p-2">
        <h6 class="card-title text-white">
        @break
    @default
        <div class="card-img-top bg-info p-2">
        <h6 class="card-title text-white">
    @endswitch
        {{humanize_date(\Illuminate\Support\Carbon::create($entry->date)->timezone($project->timezone))}}</h6>
    </div>

    <div class="card-body">
        <p class="card-text text-dark">
            {{ $entry->text }}
        </p>
    </div>
    <div class="card-footer">
        <p class="card-text fs-6 text-secondary fst-italic">
            {{$entry->user->name}}
        </p>
    </div>
</div>