<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('projects.notifications.webhooks.name')</th>
                        <th>@lang('projects.notifications.webhooks.method')</th>
                        <th>@lang('projects.notifications.webhooks.url')</th>
                        <th>@lang('projects.notifications.webhooks.fields')</th>
                        <th colspan="2">@lang('projects.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $webhooks = [];
                        if(!is_null($project->webhooks)){
                            foreach ($project->webhooks as $webhook){
                                if($webhook->type === $type)
                                    $webhooks[] = $webhook;
                            }
                        }

                    @endphp

                    @if(count($webhooks))
                        @foreach($webhooks as $webhook)
                            <tr class="{{$webhook->enabled ? '' : 'text-decoration-line-through'}}">
                                <td>{{$loop->iteration}}</td>
                                <td class="{{$webhook->enabled ? 'text-success' : 'text-secondary'}}">{{$webhook->name}}</td>
                                <td class="{{$webhook->enabled ? 'text-dark' : 'text-secondary'}}">{{$webhook->method}}</td>
                                <td class="{{$webhook->enabled ? 'text-warning' : 'text-secondary'}}">{{$webhook->url}}</td>
                                <td  class="{{$webhook->enabled ? 'text-info' : 'text-secondary'}}">
                                    @foreach ($webhook->fields as $field)
                                        {{ trans("projects.notifications.webhooks.$type.fields.$field")  . ($loop->last ? '' : ', ') }}
                                    @endforeach
                                </td>
                                <td>
                                    {!! Form::open([ 'route' => ['webhook.update', $project, $webhook->name ], 'method' => 'PUT' ]) !!}
                                        {!! Form::hidden('enabled', $webhook->enabled ? 0 : 1) !!}
                                        {!! Form::button('<i class="fa fa-power-off" aria-hidden="true"></i>', ['class' => 'btn btn-' . ($webhook->enabled ? 'primary' : 'secondary'), 'type' => 'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                                <td>
                                    {!! Form::open([ 'route' => ['webhook.destroy', $project, $webhook->name ], 'method' => 'DELETE' ]) !!}
                                        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-' . ($webhook->enabled ? 'danger' : 'secondary'), 'type' => 'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">@lang('projects.notifications.webhooks.none')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>