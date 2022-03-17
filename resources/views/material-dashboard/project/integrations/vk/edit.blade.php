@extends('material-dashboard.layouts.app')

@section('content')

    <div class="card">
        <div class="card-body">
            <h6 class="card-title text-center">Изменить данные формы</h6>
            <div class="container">
                {!! Form::open(['url' => route('vk_forms.update', [$project, $form]), 'method' => 'PUT']) !!}
                <div class="row justify-content-center mb-3">
                    {{-- Код ответа --}}
                    <div class="col-1">
                        {!! Form::label('confirmation_response', 'Код ответа', ['class' => 'form-label']) !!}
                        <div class="border rounded px-2">
                            {!! Form::text('confirmation_response', $form->confirmation_response, ['id' => 'confirmation_response', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    {{-- Идентификатор группы --}}
                    <div class="col-2">
                        {!! Form::label('group_id', 'Идентификатор группы', ['class' => 'form-label']) !!}
                        <div class="border rounded px-2">
                            {!! Form::text('group_id', $form->group_id, ['id' => 'group_id', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    {{-- Посадочная --}}
                    <div class="col-5">
                        {!! Form::label('host', 'Посадочная', ['class' => 'form-label']) !!}
                        <div class="border rounded px-2">
                            {!! Form::text('host', $form->host, ['id' => 'host', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    {{-- Источник --}}
                    <div class="col-3">
                        {!! Form::label('source', 'Источник', ['class' => 'form-label']) !!}
                        <div class="border rounded px-2">
                            {!! Form::text('source', $form->source, ['id' => 'source', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    {{-- Источник --}}
                    <div class="col-1">
                        {!! Form::submit('Сохранить', ['class' => 'btn btn-info']) !!}
                    </div>
                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
