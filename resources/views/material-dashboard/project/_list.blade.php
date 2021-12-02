<div class="card">
    <div class="table-responsive">
        <table class="table align-items-center mb-0">
            <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">@lang('projects.attributes.name')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">status</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">@lang('projects.attributes.leads_all')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">@lang('projects.attributes.leads_today')</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">@lang('projects.attributes.created_at')</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>
                        <div class="d-flex px-2">
                            <div>
                                <img src="{{ asset('media/img/project.png') }}" class="avatar avatar-sm rounded-circle me-2">
                            </div>
                            <div class="my-auto">
                                <h6 class="mb-0 text-xs">{{ link_to_route('project.journal', $project->name, $project) }}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($project->settings['enabled'])
                            <span class="badge badge-dot me-4">
                                <i class="bg-success"></i>
                                <span class="text-dark text-xs">@lang('projects.status-active')</span>
                            </span>
                        @else
                            <span class="badge badge-dot me-4">
                                <i class="bg-danger"></i>
                                <span class="text-dark text-xs">@lang('projects.status-suspended')</span>
                            </span>
                        @endif
                    </td>
                    <td>
                        <p class="text-xs font-weight-normal mb-0">{{ $project->leads->count() }}</p>
                    </td>
                    <td class="align-middle text-center">
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-xs">{{ $project->leadsToday->count() }}</span>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-xs">{{ humanize_date($project->created_at, 'd/m/Y H:i:s') }}</span>
                        </div>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-link text-secondary mb-0">
                      <span class="material-icons">
                      more_vert
                      </span>
                        </button>
                    </td>
                    <td>
                        <a href="{{ route('project.journal', $project) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        {!! Form::model($project, ['method' => 'DELETE', 'route' => ['project.destroy', $project], 'class' => 'form-inline', 'data-confirm' => __('forms.projects.delete')]) !!}
                        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['class' => 'btn btn-danger btn-sm', 'name' => 'submit', 'type' => 'submit']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{--{{ trans_choice('projects.count', $projects->count()) }}--}}
