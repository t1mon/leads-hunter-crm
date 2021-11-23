@extends('material-dashboard.layouts.app')

@section('content')
    <div class="card w-25 d-flex align-items-center">
        <div class="card-body">
            <h6 class="card-title text-center">
                Комментарий к лиду "{{$lead->getClientName()}}"
            </h6>

            <p class='card-text'>{{$comment->comment_body}}</p>
            <p class="card-text text-end text-info">Автор: {{$comment->user->name}}</p>
            <p class="card-text text-secondary fst-italic">Дата: {{humanize_date($comment->created_at)}}</p>
        </div>
        @if($project->isOwner() or Auth::user()->isManagerFor($project))
            <div class="card-footer">
                {!! Form::model($comment, ['id' => 'delete-form', 'method' => 'DELETE', 'route' => ['comment.destroy', $project, $lead, $comment] ]) !!}
                {!! Form::close() !!}

                <div class="btn-group">
                    <a class="btn btn-primary" href="{{route('comment.edit', [$project, $lead, $comment])}}">
                        @lang('projects.button-change')
                    </a>
                    {!! Form::submit(trans('projects.button-delete'), ['class' => 'btn btn-danger', 'form' => 'delete-form'] ) !!}
                </div>

                <p class="card-text">
                    
                </p>
            </div>
        @endif
    </div>

@endsection