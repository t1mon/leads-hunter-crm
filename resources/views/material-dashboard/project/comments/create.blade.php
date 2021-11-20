@extends('material-dashboard.layouts.app')

@section('content')
    @php
        $comment = \App\Models\Project\Lead\Comment::where(
            ['project_id' => $project->id, 'lead_id' => $lead->id]
            )->first();
    @endphp    

    <div class="card">
        {!! Form::open( ['route' => ['comment.store', $project, $lead], ] ) !!}
        <div class="card-body">
            <h6 class="card-title">
                Комментарий к проекту "{{$lead->project->name}}"
            </h6>
            <p class='card-text'>
                {!! Form::textarea(
                    'comment_body',
                    is_null($comment) ? null : $comment->comment_body,
                    [
                        'class' => 'form-control',
                        'placeholder' => 'Текст комментария',
                    ]
                ) !!}
            </p>
        </div>

        <div class="card-footer">
            <p class='card-text'>
                {!! Form::submit('Отправить', ['class' => 'btn btn-primary']); !!}
            </p>
        </div>
        {!! Form::close() !!}
    </div>

@endsection