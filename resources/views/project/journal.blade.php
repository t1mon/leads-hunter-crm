@extends('layouts.project')

@section('content')
    @php
        $phones = [];
        $tableId = 1;
    @endphp
{{--    <form action="{{ route('project.journal', $project) }}">--}}
{{--        <input type="text" name="client">--}}
{{--        <button type="submit">Отправить</button>--}}
{{--    </form>--}}
    {!! Form::open(['route' => ['project.journal', $project], 'class' => 'd-flex', 'method' => 'GET']) !!}
<div class="form-row">
    <div class="form-group col-md-4">
        {!! Form::text('date_from',request()->date_from ?? null , ['class' => 'form-control', 'placeholder' => 'Дата от']) !!}
    </div>
    <div class="form-group col-md-4">
        {!! Form::text('date_to',request()->date_to ?? \Illuminate\Support\Carbon::now()->format('d-m-Y'), ['class' => 'form-control', 'placeholder' => ' до']) !!}
    </div>
    <div class="form-group col-md-4">
        <div class="checkbox">
            <label class="text-light">
                {!! Form::checkbox('double_phone', null, request()->double_phone) !!} Убрать дубликаты
            </label>
        </div>
    </div>
    <div class="form-group col-md-12">
    <button type="submit" class="btn btn-primary">
        Применить
    </button>
    </div>
</div>
    {!! Form::close() !!}
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-dark ">

            <thead>
            <tr>
                <th>#</th>
                <th>@lang('projects.journal.date')</th>
                <th>@lang('projects.journal.client')</th>
                <th>@lang('projects.journal.phone')</th>
                <th>@lang('projects.journal.host')</th>
                <th>@lang('projects.journal.source')</th>
                <th>@lang('projects.journal.utm.utm_source')</th>
                <th>@lang('projects.journal.utm.utm_campaign')</th>
                <th>@lang('projects.journal.utm.utm_medium')</th>
                <th>@lang('projects.journal.utm.utm_content')</th>
                <th>@lang('projects.journal.utm.utm_term')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($leads as $count => $lead)
                @if(request()->has('double_phone') && !empty(request()->double_phone))
                    @php
                        if (in_array($lead->phone, $phones)) {
                            continue;
                        }
                        $phones[] = $lead->phone;
                    @endphp
                @endif
                <tr>
                    <td class="text-nowrap">{{ $tableId++ }}</td>
                    <td class="text-nowrap">{{ humanize_date($lead->created_at, 'd-m-Y H:i:s') }}</td>
                    <td class="text-nowrap">{{  $lead->getClientName() }}</td>
                    <td class="text-nowrap">{{ phone_format($lead->phone) }}</td>
                    <td class="text-nowrap">{{ $lead->host }}</td>
                    <td class="text-nowrap">{{ $lead->referrer }}</td>
                    <td class="text-nowrap">{{ $lead->utm['utm_source'] ?? '' }}</td>
                    <td class="text-nowrap">{{ $lead->utm['utm_campaign'] ?? '' }}</td>
                    <td class="text-nowrap">{{ $lead->utm['utm_medium'] ?? ''}}</td>
                    <td class="text-nowrap">{{ $lead->utm['utm_content'] ?? ''}}</td>
                    <td class="text-nowrap">{{ $lead->utm['utm_term'] ?? ''}}</td>
                </tr>
            @endforeach
            </tbody>

            @if(request()->has('double_phone') && !empty(request()->double_phone))
                <caption>{{ trans_choice('projects.journal.count_unique', count($phones)) }}</caption>
            @else
                <caption>{{ trans_choice('projects.journal.count', $leads->total()) }}</caption>
            @endif
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $leads->links() }}
    </div>

@endsection

