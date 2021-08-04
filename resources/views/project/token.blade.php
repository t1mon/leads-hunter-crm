@extends('layouts.project')

@section('content')


      <h1>@lang('projects.integrated')</h1>
      <hr class="my-4">
      <div class="form-group">
          {!! Form::label('project_id', __('projects.attributes.project_id')) !!}
          {!! Form::text('project_id', $project->id, ['class' => 'form-control', 'readonly']) !!}
      </div>
      <div class="form-group">
        {!! Form::label('api_token', __('projects.attributes.api_token')) !!}
        {!! Form::text('api_token', $project->api_token ?? __('projects.empty_api_token'), ['class' => 'form-control', 'readonly']) !!}
      </div>

      <div class="d-flex justify-content-start">
        {!! Form::model($project, ['method' => 'PATCH', 'route' => ['project.token.update', $project], 'class' => 'ml-auto']) !!}
          {!! Form::submit(__('forms.actions.generate'), ['class'=> 'btn btn-success', 'data-confirm' => __('forms.tokens.generate')]) !!}
        {!! Form::close() !!}
      </div>

@endsection
