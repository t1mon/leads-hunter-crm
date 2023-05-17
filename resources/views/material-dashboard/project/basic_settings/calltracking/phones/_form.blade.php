<div class="card">
    <div class="card-body">
        @isset($phone)
            <h5 class="card-title text-center">Изменить телефон</h5>
        @else
            <h5 class="card-title text-center">Добавить телефон</h5>
        @endisset

        <p class="card-text text-center">
            {!! Form::text('phone', isset($phone->phone ) ? $phone->phone : null , ['class' => 'form-control border p-2', 'placeholder' => 'Номер телефона']) !!}
            {!! Form::hidden('project_id', $project->id) !!}
        </p>

        <p class="card-text text-center">
            {!! Form::submit('Сохранить', ['class' => 'btn btn-sm btn-primary']) !!}
        </p>

    </div>
</div>
