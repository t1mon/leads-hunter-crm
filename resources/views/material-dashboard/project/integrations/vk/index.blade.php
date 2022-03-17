<div class="container">
    {{-- Кнопка включения/отключения --}}
    <div class="row justify-content-center my-3">
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">
                        {{$project->settings['vk']['enabled'] ? 'Выключить ' : 'Включить '}} приём заявок с ВКонтакте
                    </h1>
                    {!! Form::model($project, ['route' => ['project.update', $project], 'method' => 'PUT']) !!}
                        {!! Form::hidden('settings[vk][enabled]', !$project->settings['vk']['enabled']) !!}
                        <div class="text-center">
                            <button class="btn {{$project->settings['vk']['enabled'] ? 'btn-danger' : 'btn-secondary'}} btn-lg">
                                <i class="fa fa-power-off" aria-hidden="true"></i>
                            </button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    {{-- Форма для добавления (указывается отдельно, поскольку её нельзя поместить в таблицу) --}}
    {!! Form::open(['url' => route('vk_forms.store', $project), 'method' => 'CREATE', 'id' => 'vk_add']) !!}
        {!! Form::hidden('project_id', $project->id) !!}
    {!! Form::close() !!}
    <div class="card my-3">
        <div class="card-body">
            <h5 class="card-title text-center"> Адрес: {{ \Illuminate\Support\Facades\URL::route('vk.webhook',$project) }}</h5>
        </div>
        
    </div>
    {{-- Таблица с формами --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-center mb-3">Список форм</h4>
            <table class="table table-hover table-bordered table align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Ответ</th>
                        <th>Идентификатор группы</th>
                        <th>Посадочная</th>
                        <th>Источник</th>
                        <th>Статус</th>
                        <th colspan="3">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Форма для добавления --}}
                    <tr>
                        <td class="text-info fw-bold">Новая:</td>
                        <td>
                            <div class="border border-dark rounded-1 px-2">
                                {!! Form::text('confirmation_response', null, ['class' => 'form-control', 'form' => 'vk_add']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="border border-dark rounded-1 px-2">
                                {!! Form::text('group_id', null, ['class' => 'form-control', 'form' => 'vk_add']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="border border-dark rounded-1 px-2">
                                {!! Form::text('host', null, ['class' => 'form-control', 'form' => 'vk_add']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="border border-dark rounded-1 px-2">
                                {!! Form::text('source', null, ['class' => 'form-control', 'form' => 'vk_add']) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" name="enabled" class="form-check-input" value="1" form="vk_add" id="form-enable" checked>
                                {!! Form::label('form-enable', 'Включить', ['class' => 'form-check-label']) !!}
                            </div>
                        </td>
                        <td colspan="3">
                            {!! Form::submit('Сохранить', ['class' => 'btn btn-info', 'form' => 'vk_add']) !!}
                        </td>
                    </tr>

                    {{-- Список форм --}}
                    @if ($project->vk_forms->count())
                        @foreach ($project->vk_forms as $form)
                            <tr class="{{$form->enabled ? 'text-info' : 'text-secondary'}}">
                                <td>{{$form->id}}</td>
                                <td>{{$form->confirmation_response}}</td>
                                <td>{{$form->group_id}}</td>
                                <td>
                                    @if(strlen($form->host) > 20) {{-- Сокращение адреса для удобства --}}
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{$form->host}}">
                                            {{substr($form->host, 0, 20)}}...
                                        </span>
                                    @else
                                        {{$form->host}}
                                    @endif
                                </td>
                                <td>{{$form->source}}</td>
                                <td>{{$form->enabled ? 'Включена' : 'Выключена' }}</td>
                                <td>
                                    {!! Form::open(['url' => route('vk_forms.toggle', [$project, $form]), 'method' => 'POST']) !!}
                                    <button class="btn btn-{{$form->enabled ? 'primary' : 'secondary'}} btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$form->enabled ? 'Выключить' : 'Включить'}}">
                                        <i class="fa fa fa-power-off" aria-hidden="true"></i>
                                    </button>
                                    {!! Form::close() !!}
                                </td>
                                <td>
                                    <a href="{{route('vk_forms.edit', [$project, $form])}}" class="btn btn-{{$form->enabled ? 'info' : 'secondary'}} btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Редактировать">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    {!! Form::model($form, ['route' => ['vk_forms.destroy', [$project, $form]], 'method' => 'DELETE']) !!}
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Удалить">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-danger">В данный момент в базе данных нет записей</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>


</div>

