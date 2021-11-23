@extends('material-dashboard.layouts.app')

@section('content')
    <div class="card">
        {!! Form::open( ['route' => ['comment.store', $project, $lead], ] ) !!}
        <div class="card-body">
            <h6 class="card-title">
                Комментарий к лиду "{{$lead->getClientName()}}"
            </h6>
            <p class='card-text'>
                {!! Form::textarea(
                    'comment_body',
                    isset($comment) ? $comment->comment_body : null,
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