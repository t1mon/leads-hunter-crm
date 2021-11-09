@extends('material-dashboard.layouts.app')

@section('content')
    @php
        $phones = [];
        $tableId = 1;
    @endphp
    {{--    <form action="{{ route('project.journal', $project) }}">--}}
    {{--        <input type="text" name="client">--}}
    {{--        <button type="submit">Отправить</button>--}}
    {{--    </form>--}}
  <div class="row">
    {!! Form::open(['route' => ['project.journal', $project], 'class' => 'd-flex', 'method' => 'GET']) !!}
<div class="col-2">
        <div class="input-group input-group-static my-3 p-2">
{{--            {!! Form::text('date_from',request()->date_from ?? null , ['class' => 'form-control' . ($errors->has('date_from') ? ' is-invalid' : ''), 'placeholder' => 'Дата от']) !!}--}}
            <label>Дата от</label>
            <input type="date" class="form-control {{ ($errors->has('date_from') ? ' is-invalid' : '') }}" name="date_from" value="{{ ( request()->date_from ? request()->date_from : '') }}">
            @error('date_from')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror

        </div>
  </div>
      <div class="col-2">
        <div class="input-group input-group-static my-3 p-2">
{{--            {!! Form::text('date_to',request()->date_to ?? \Illuminate\Support\Carbon::now()->format('d-m-Y'), ['class' => 'form-control' . ($errors->has('date_to') ? ' is-invalid' : ''), 'placeholder' => ' до']) !!}--}}
            <label> до</label>
            <input type="date" class="form-control {{ ($errors->has('date_to') ? ' is-invalid' : '') }}" name="date_to" value="{{ (request()->date_to ? request()->date_to : '') }}">
            @error('date_to')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
      </div>
      <div class="col-4">
          <div class="form-check form-switch ps-2">
                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0">
                    {!! Form::checkbox('double_phone', null, request()->double_phone,['class' => 'form-check-input ms-auto' ]) !!} Убрать дубликаты
                </label>
          </div>
      </div>
      <div class="col-4">
        <div class="form-group col-md-12">
            <button type="submit" class="btn btn-primary">
                Применить
            </button>
        </div>
      </div>

    {!! Form::close() !!}

  </div>

<div class="row my-4">
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-xxs font-weight-bolder opacity-7">#</th>
                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.date')</th>
                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.client')</th>
                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.phone')</th>
                        <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">№</th>
                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.host')</th>
                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.source')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leads as $count => $lead)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <p class="text-sm font-weight-normal mb-0">{{ $tableId++ }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-center text-sm font-weight-normal mb-0">{{ humanize_date($lead->created_at, 'd-m-Y H:i:s') }}</p>
                            </td>
                            <td>
                                <h6 class="text-center mb-0 font-weight-normal text-sm">{{  $lead->getClientName() }}</h6>
                            </td>
                            <td class="align-middle text-center text-sm">
                                <p class="mb-0 font-weight-normal text-sm">{{ phone_format($lead->phone) }}</p>
                            </td>
                            <td>
                                <div class="text-center">
                              <span class="badge badge-dot me-4">
                                  @if($lead->entries === 1 ) <i class="bg-success"></i> @endif
                                  @if($lead->entries === 2 ) <i class="bg-warning"></i> @endif
                                  @if($lead->entries > 2 ) <i class="bg-danger"></i> @endif
                                <span class="text-dark text-xs"> {{ $lead->entries }}</span>
                              </span>
                                </div>
                            </td>
                            <td class="align-middle text-center">
                                <p class="text-sm font-weight-normal mb-0">{{ $lead->host }}</p>
                            </td>
                            <td class="align-middle text-center">
                                <p class="text-sm font-weight-normal mb-0">{{ parse_url($lead->referrer , PHP_URL_HOST)  }}</p>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    <div class="content-left">
        {{ trans_choice('projects.journal.count', $leads->total()) }}
    </div>
    <div class="d-flex justify-content-center">
        {{ $leads->links() }}
    </div>
@endsection
