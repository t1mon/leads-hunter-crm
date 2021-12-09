@extends('material-dashboard.layouts.app')

@section('content')
    @php
        $phones = [];
        $tableId = 1;

        $permissions = Auth::user()->getPermissionsForProject($project);
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
            <input type="date" class="form-control {{ ($errors->has('date_from') ? ' is-invalid' : '') }}" name="date_from" value="{{ ( request()->date_from ? request()->date_from : null) }}">
            @error('date_from')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror

        </div>
  </div>
      <div class="col-2">
        <div class="input-group input-group-static my-3 p-2">
{{--            {!! Form::text('date_to',request()->date_to ?? \Illuminate\Support\Carbon::now()->format('d-m-Y'), ['class' => 'form-control' . ($errors->has('date_to') ? ' is-invalid' : ''), 'placeholder' => ' до']) !!}--}}
            <label> до</label>
            <input type="date" class="form-control {{ ($errors->has('date_to') ? ' is-invalid' : '') }}" name="date_to" value="{{ (request()->date_to ? request()->date_to : null) }}">
            @error('date_to')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
      </div>
      <div class="col-4">
          <div class="form-check form-switch ps-2">
                <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0">
                    <input name="double_phone" type="checkbox" class="form-check-input ms-auto" value="true" {{ (request()->double_phone ? 'checked' : '') }} >
                        Убрать дубликаты
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

{{--Ссылки на скачивание--}}
<div class="card my-2">
    <div class="card-body text-center">
        <span class="me-6">
            Скачать записи за сегодня:
            <a href="{{route('project.journal.download', ['project' => $project, 'format' => \Maatwebsite\Excel\Excel::XLSX])}}" class="card-link">.xlsx</a>
            <span>|</span>
            <a href="{{route('project.journal.download', ['project' => $project, 'format' => \Maatwebsite\Excel\Excel::CSV])}}" class="card-link">.csv</a>
        </span>        
    </div>
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
                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.class')</th>
                        <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.phone')</th>
                        <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">№</th>
                        <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Комментарий</th>

                        {{--Если пользователь создатель или менеджер проекта, ему видны все колонки --}}
                        @if($project->isOwner() or Auth::user()->isManagerFor($project))
                            <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.host')</th>
                            <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">@lang('projects.journal.source')</th>
                        @else {{--Если пользователь наблюдатель, ему видны только колонки согласно настройкам--}}
                            @foreach($permissions->view_fields as $field)
                            <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">
                                @lang('projects.journal.' . $field)
                            </th>
                            @endforeach
                        @endif
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
                                <p class="text-center text-sm font-weight-normal mb-0">
                                    {{-- {{ humanize_date($lead->created_at, 'd-m-Y H:i:s') }} --}}
                                    {{ humanize_date(\Illuminate\Support\Carbon::create($lead->updated_at)->timezone($project->timezone), 'd-m-Y H:i:s') }}
                                </p>
                            </td>
                            <td>
                                <h6 class="text-center mb-0 font-weight-normal text-sm">{{  $lead->getClientName() }}</h6>
                            </td>
                            <td class="text-white text-center" style="background-color:#{{$lead->class->color ?? ''}}">
                                @if(Auth::user()->isInProject($project))
                                    @php
                                        $classes = [];
                                        $classes[0] = 'Убрать';
                                        foreach($project->classes as $class)
                                            $classes[$class->id] = $class->name;
                                        
                                    @endphp
                                    
                                    {!! Form::open([
                                        'method' => 'POST',
                                        'route' => ['class-assign', [$project, $lead] ],
                                    ]) !!}
                                    
                                    {!! Form::select('class_id', $classes, $lead->class->id ?? $classes[0] ) !!}

                                    {!! Form::button(
                                        '<i class="fa fa-save" aria-hidden="true"></i>',
                                        [
                                            'type' => 'submit',
                                            'class' => 'btn btn-primary btn-sm',
                                        ]) !!}

                                    {!! Form::close() !!}
                                @else
                                        {{$lead->class->name ?? ''}}
                                @endif
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

                            <td class="align-middle text-center text-sm">
                                @php
                                    $comment = \App\Models\Project\Lead\Comment::where(
                                        ['project_id' => $project->id, 'lead_id' => $lead->id]
                                        )->first();
                                @endphp

                                @if(is_null($lead->comment_CRM))
                                    @if(Auth::user()->isInProject($project))
                                        <a class="fa fa-plus" aaria-hidden="true" href="{{route('comment.create', [$project, $lead])}}"></a>
                                    @endif
                                @else
                                    <a href="{{route('comment.show', [$project, $lead, $lead->comment_CRM])}}" class="link-info">
                                        {{ mb_substr($lead->comment_CRM->comment_body, 0, 15) . (mb_strlen($lead->comment_CRM->comment_body) > 15 ? '…' : '') }}
                                    </a>
                                @endif
                            </td>

                            {{--Если пользователь создатель или администратор проекта, ему видны все колонки --}}
                            @if($project->isOwner() or Auth::user()->isManagerFor($project))
                                <td class="align-middle text-center">
                                    <p class="text-sm font-weight-normal mb-0">{{ $lead->host }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-sm font-weight-normal mb-0">{{ parse_url($lead->referrer , PHP_URL_HOST)  }}</p>
                                </td>
                            @else {{--Если пользователь наблюдатель, ему видны только колонки согласно настройкам--}}
                                @foreach($permissions->view_fields as $field)
                                    <th class="align-middle text-center">
                                        <p class="text-sm font-weight-normal mb-0">{{ $lead->$field }}</p>
                                    </th>
                                @endforeach
                            @endif
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
