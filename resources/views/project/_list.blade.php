<div class="table-responsive-sm">
    <table class="table ">
        <caption>{{ trans_choice('projects.count', $projects->total()) }}</caption>
        <thead>
        <tr>
            <th>@lang('projects.attributes.name')</th>
            <th>@lang('projects.attributes.leads_all')</th>
            <th>@lang('projects.attributes.leads_today')</th>
            <th>@lang('projects.attributes.created_at')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $project)
            <tr>
                <td>{{ link_to_route('project.journal', $project->name, $project) }}</td>
                <td>{{ $project->leads->count() }}</td>
                <td>{{ $project->leadsToday->count() }}</td>
                <td>{{ humanize_date($project->created_at, 'd/m/Y H:i:s') }}</td>
                <td>
                    <a href="{{ route('project.journal', $project) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>

                    {!! Form::model($project, ['method' => 'DELETE', 'route' => ['project.destroy', $project], 'class' => 'form-inline', 'data-confirm' => __('forms.projects.delete')]) !!}
                    {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'name' => 'submit', 'type' => 'submit']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center">
    {{ $projects->links() }}
</div>
