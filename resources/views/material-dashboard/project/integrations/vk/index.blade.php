<div class="container">
    {{-- Кнопка включения/отключения --}}
    <div class="row justify-content-center my-3">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">Включить приём заявок с ВКонтакте</h1>
                    {!! Form::model($project, ['route' => ['project.update', $project], 'method' => 'PUT']) !!}
                        {!! Form::hidden('settings[vk][enabled]', true) !!}
                        <div class="text-center">
                            <button class="btn btn-danger btn-lg">
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
    {!! Form::close() !!}
    <div>
        <h4> Адрес: {{ \Illuminate\Support\Facades\URL::route('vk.webhook',$project) }}</h4>
    </div>
    {{-- Таблица с формами --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered table align-middle text-center">
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
                            <tr>
                                <td>{{$form->id}}</td>
                                <td>{{$form->confirmation_response}}</td>
                                <td>{{$form->group_id}}</td>
                                <td>{{$form->host}}</td>
                                <td>{{$form->source}}</td>
                                <td>{{$form->enabled ? 'Включен' : 'Выключен' }}</td>
                                <td>
                                    {!! Form::model($form, ['route' => ['vk_forms.destroy', [$project, $form]], 'method' => 'DELETE']) !!}
                                    <button class="btn btn-danger">
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

