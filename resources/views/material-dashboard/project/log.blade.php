@extends('material-dashboard.layouts.app')

@section('content')
    @if(count($entries))
        @foreach ($entries as $entry)
            <div class="card my-3">
                <div class="card-img-top bg-info p-2">
                    <h6 class="card-title text-white">{{humanize_date(\Illuminate\Support\Carbon::create($entry->date))}}</h6>
                </div>

                <div class="card-body">
                    <p class="card-text text-dark">
                        <span class="fw-bold me-2">
                            [{{$entry->lead->name}}, {{phone_format($entry->lead->phone)}}]
                        </span>
                        {{ $entry->text }}
                    </p>
                </div>
            </div>
        @endforeach
    @else
        <div class="card my-3">
            <div class="card-body">
                <p class="card-text">Записей по проекту нет</p>
            </div>
        </div>
    @endif

@endsection