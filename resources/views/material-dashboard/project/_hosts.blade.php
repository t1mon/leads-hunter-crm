{{--Форма добавления нового хоста--}}
<div class="card">
    {!! Form::open(['route' => ['host.store',$project], 'class' => 'd-flex']) !!}
        <div class="form-row">
            <div class="form-group col-md-12">
                {!! Form::text('host', null, ['class' => 'form-control', 'placeholder' => 'Адрес хоста']) !!}
                {!! Form::hidden('project_id', $project->id) !!}
            </div>
            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary">
                    @lang('projects.button-add')
                </button>
            </div>
    
        </div>
    {!! Form::close() !!}
</div>

{{--Таблица с хостами--}}
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>@lang('projects.hosts.host')</th>
                    <th>@lang('projects.hosts.action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hosts as $host)
                    <tr>
                        <td class="text-nowrap">{{$host->host}}</td>
                        <td class="text-nowrap">
                            {!! Form::model($host, ['method' => 'DELETE', 'route' => ['host.destroy', [$project,$host]]]) !!}
                            {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'name' => 'submit', 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
