<table class="table table-striped table-sm table-responsive-md">
    <caption>{{ trans_choice('projects.count', $projects->total()) }}</caption>
    <thead>
        <tr>
            <th>@lang('projects.attributes.title')</th>
            <th>@lang('projects.attributes.author')</th>
            <th>@lang('projects.attributes.projected_at')</th>
            <th><i class="fa fa-comments" aria-hidden="true"></i></th>
            <th><i class="fa fa-heart" aria-hidden="true"></i></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
            <tr>
                <td>{{ link_to_route('project.show', $project->name, $project) }}</td>
                <td></td>
                <td>{{ humanize_date($project->created_at, 'd/m/Y H:i:s') }}</td>
                <td><span class="badge badge-pill badge-secondary">{{ $project->comments_count }}</span></td>
                <td><span class="badge badge-pill badge-secondary">{{ $project->likes_count }}</span></td>
                <td>
                    <a href="{{ route('project.edit', $project) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>

                    {!! Form::model($project, ['method' => 'DELETE', 'route' => ['project.destroy', $project], 'class' => 'form-inline', 'data-confirm' => __('forms.projects.delete')]) !!}
                        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'name' => 'submit', 'type' => 'submit']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $projects->links() }}
</div>
