@extends("material-dashboard.layouts.app")
@section("content")

@isset($response)
    <div class="card my-3">
        <div class="card-body">
            <h5 class="card-title">Получен ответ</h6>
            <p class="card-text">{{$response}}</p>
        </div>
    </div>    
@endisset


<div class="row row-cols-1 row-cols-md-3 g-4">
    {{--Просмотреть обновления--}}
    <div class="col">
        <div class="card my-3">
            <div class="card-body">
                <a class="btn btn-primary" href="{{route('telegram.updates')}}">
                    Просмотреть обновления
                </a>
            </div>
        </div>
    </div>

    {{--Настройки вебхука--}}
    <div class="col">
        <div class="card my-3">
            <div class="card-body">
                <h5 class="card-title">Настройки вебхука</h5>
                @php
                    $webhook = \App\Models\Project\TelegramID::API_GetWebhook();
                @endphp
                @if($webhook['ok'])
                    <p class="card-text">
                        {{strlen($webhook['result']['url']) ? $webhook['result']['url'] : 'Вебхук не назначен'}}
                    </p>

                    @if(strlen($webhook['result']['url']))
                        <h6 class="card-title">Информация</h6>
                        <p class="card-text">
                            @foreach($webhook['result'] as $field => $value)
                                <p class="card-text">
                                    @if($field !== 'url' and $field !== 'allowed_updates')
                                        {{$field}}: {{$value}}
                                    @endif
                                </p>
                            @endforeach
                        </p>
                    @endif 

                    <a class="btn btn-primary" href="{{route('telegram.webhook.update')}}">
                        Обновить
                    </a>

                    @if(strlen($webhook['result']['url']))
                        <a class="btn btn-primary" href="{{route('telegram.webhook.delete')}}">
                            Удалить
                        </a>
                        <a class="btn btn-primary" href="{{route('telegram.webhook.info')}}">
                            Подробнее
                        </a>
                    @endif
                @else
                    <p class="card-text">ОШИБКА ОТПРАВКИ ЗАПРОСА</p>
                @endif
            </div>
        </div>
    </div>

    {{--Отправить сообщение по идентификатору--}}
    <div class="col">
        <div class="card my-3">
            <div class="card-body">
                <h5 class="card-title">Отправить сообщение по идентификатору</h5>
                @if($contacts->isEmpty())
                    <p class="card-text">В базе нет контактов</p>
                @else
                {!! Form::open(["method" => "get"]) !!}
                    {!! Form::text("chat_id", null, ["placeholder" => "Идентификатор пользователя/чата", "class" => "form-control"]) !!}

                    {!! Form::selectRange("contact_id", $contacts->first()->id, $contacts->last()->id) !!}

                    {!! Form::textarea("text", null, ["placeholder" => "Сообщение", "class" => "form-control"]) !!}
                    {!! Form::submit("Отправить", ["class" => "btn btn-primary"]) !!}
                {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>

    {{--Сделать рассылку по проекту--}}
    <div class="col">
        <div class="card my-3">
            <div class="card-body">
                <h5 class="card-title">Сделать рассылку по проекту</h5>
                {!! Form::open(["method" => "get"]) !!}
                    
                    {!! Form::textarea("text", null, ["placeholder" => "Сообщение", "class" => "form-control"]) !!}
                    {!! Form::submit("Отправить", ["class" => "btn btn-primary"]) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
</div>





@endsection