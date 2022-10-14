@extends('material-dashboard.layouts.app')

@section('content')
    <div class="row">
        <div class="col">
            <h1 class="h1 text-uppercase">Интеграции Mango Office</h1>
        </div>

        {{-- Кнопка "Добавить" --}}
        <div class="row justify-content-center">
            <div class="col-auto">
                <a href="{{route('project.integrations.mango.create', $project->id)}}" class="btn btn-primary">
                    Добавить
                </a> 
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-auto">
                <table class="table table-striped table-hover table-responsive align-items-center">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($integrations->isNotEmpty())
                            @foreach ($integrations as $integration)
                                <tr>
                                    <td>
                                        <a href="{{route('project.integrations.mango.show', $integration->id)}}">
                                            {{$integration->name}}
                                        </a>
                                    </td>
                                    
                                    @if ($integration->enabled)
                                        <td class="text-primary">Активна</td>
                                    @else
                                        <td class="text-secondary">Отключена</td>
                                    @endif

                                    <td>
                                        <a href="{{route('project.integrations.mango.toggle', $integration->id)}}" class="btn btn-sm btn-primary mx-1">
                                            <i class="fa fa-power-off fs-6" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{route('project.integrations.mango.edit', $integration->id)}}" class="btn btn-sm btn-primary mx-1">
                                            <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                        </a>

                                        <a href="" class="btn btn-sm btn-primary mx-1">
                                            <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-secondary text-center">
                                <td colspan="3">
                                    Интеграций нет
                                    <a href="{{route('project.integrations.mango.create', $project->id)}}" class="link-info" style="font-size:0.7rem">
                                        Добавить…
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection