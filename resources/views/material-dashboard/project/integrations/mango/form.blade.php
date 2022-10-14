
<div class="row my-2">
    {!! Form::label('name', 'Название:', ['class' => 'col-5 col-form-label']) !!}
    <div class="col-6">
        <div class="input-group input-group-outline">
            {!! Form::text('name', $integration->name ?? null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Название интеграции']) !!}
        </div>
    </div>
</div>

<div class="row my-2">
    {!! Form::label('vpbx_api_key', 'Уникальный код вашей АТС:', ['class' => 'col-5 col-form-label']) !!}
    <div class="col-6">
        <div class="input-group input-group-outline">
            {!! Form::text('vpbx_api_key', $integration->vpbx_api_key ?? null, ['class' => 'form-control', 'id' => 'vpbx_api_key', 'placeholder' => 'Уникальный код вашей АТС']) !!}
        </div>
    </div>
</div>

<div class="row my-2">
    {!! Form::label('vpbx_api_salt', 'Ключ для создания подписи:', ['class' => 'col-5 col-form-label']) !!}
    <div class="col-6">
        <div class="input-group input-group-outline">
            {!! Form::text('vpbx_api_salt', $integration->vpbx_api_salt ?? null, ['class' => 'form-control', 'id' => 'vpbx_api_salt', 'placeholder' => 'Ключ для создания подписи']) !!}
        </div>
    </div>
</div>

<div class="row my-3 justify-content-center">
    <div class="col text-center">
        <div class="form-check">            
            {!! Form::hidden('enabled', 0) !!}
            <input type="checkbox" name="enabled" id="enabled" value="1" {{ ($integration->enabled ?? true) ? 'checked' : '' }} class="form-check-input">
            
            {!! Form::label('enabled', 'Включить', ['class' => 'form-check-label']) !!}
        </div>
    </div>
</div>