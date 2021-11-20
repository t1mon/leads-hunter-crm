@extends('material-dashboard.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">
                Комментарий к проекту {{$comment->project->name}}
            </h6>

            <p class='card-text'>{{$comment->comment_body}}</p>
        </div>
        <div class="card-footer">
            <p class="card-text">Автор: {{$comment->user->name}}</p>
            <p class="card-text">Дата: {{humanize_date($comment->created_at)}}</p>

            <p class="card-text">
                <a class="btn btn-primary" href="{{route('comment.edit', [$project, $lead, $comment])}}">
                    Изменить
                </a>
            </p>

            <p class="card-text">
                {!! Form::model($comment, ['method' => 'DELETE', 'route' => ['comment.destroy', $project, $lead, $comment] ]) !!}
                    {!! Form::submit('Удалить', ['class' => 'btn btn-danger'] ) !!}
                {!! Form::close() !!}
            </p>
        </div>
    </div>

@endsection