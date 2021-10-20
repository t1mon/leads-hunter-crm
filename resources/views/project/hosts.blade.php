@extends ('layouts.project')

@section('content')

{!! Form::open(['route' => ['host.store'], 'class' => 'd-flex']) !!}

    <div class="form-row">
        <div class="form-group col-md-12">
            {!! Form::text('host', null, ['class' => 'form-control', 'placeholder' => 'Адрес хоста']) !!}
            {!! Form::hidden('project_id', $project->id) !!}
        </div>
        <div class="form-group col-md-12">
            <button type="submit" class="btn btn-primary">
                @lang('projects.hosts.add-button')
            </button>
        </div>

    </div>

{!! Form::close() !!}

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-dark ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('projects.hosts.host')</th>
                    <th>@lang('projects.hosts.action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hosts as $host)
                    <tr>
                        <td class="text-nowrap">{{$host->id++}}</td>
                        <td class="text-nowrap">{{$host->host}}</td>
                        <td class="text-nowrap"><a href=''>@lang('projects.hosts.delete')</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
