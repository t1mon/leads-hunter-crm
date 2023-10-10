@extends('material-dashboard.layouts.app')

@section('content')
    {{--Содержимое вкладок--}}
    <div class="container">
        @if($entries->isNotEmpty())
            <div class="row justify-content-center my-2">
                <div class="col-auto text-center">
                    <a href="{{$entries->previousPageUrl()}}" class="btn btn-primary">Назад</a>
                </div>

                <div class="col-auto text-center">
                    <a href="{{$entries->nextPageUrl()}}" class="btn btn-primary">Далее</a>
                </div>
            </div>

            @foreach ($entries as $entry)
                <div class="row justify-content-center my-1">
                    <div class="col">
                        @include('material-dashboard.project.log._card-project')
                    </div>
                </div>
            @endforeach
        @else
            <div class="row justify-content-center my-1">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text text-center text-bold">Записи по данному проекту отсутствуют</p>
                        </div>
                    </div>
                </div>
            </div>
        @endempty
    </div>
@endsection