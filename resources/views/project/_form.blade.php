<div class="form-group">
    {!! Form::label('name', __('posts.attributes.title')) !!}
    {!! Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder'=> 'Введите имя нового проекта', 'required']) !!}

    @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

