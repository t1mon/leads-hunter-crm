<div class="card">
    <div class="card-body">
        <h6 class="card-title text-center">@lang('projects.notifications.info.info')</h6>
        <div class="table-responsive">
            <table class="table text-center align-middle">
                <thead>
                    <tr>
                        <th>@lang('projects.notifications.info.service')</th>
                        <th>@lang('projects.notifications.info.status')</th>
                        <th>@lang('projects.notifications.info.additional')</th>
                        <th>@lang('projects.notifications.emails_fields')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-{{$project->settings['email']['enabled'] ? 'success' : 'danger'}}">E-mail</td>
                        <td>
                            <span class="badge badge-dot">
                                <i class="bg-{{$project->settings['email']['enabled'] ? 'success' : 'secondary'}}"></i>
                            </span>
                        </td>
                        <td></td>
                        <td>
                            <span class="text-{{$project->settings['email']['enabled'] ? 'info' : 'secondary'}}">
                                @foreach ($project->settings['email']['fields'] as $field)
                                    {{ trans('projects.journal.' . $field)  . ($loop->last ? '' : ', ') }}
                                @endforeach
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-{{$project->settings['telegram']['enabled'] ? 'success' : 'danger'}}">Telegram</td>
                        <td>
                            <span class="badge badge-dot">
                                <i class="bg-{{$project->settings['telegram']['enabled'] ? 'success' : 'secondary'}}">
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-dot me-4">
                                <i class="bg-{{is_null($project->telegram_channel_id) ? 'secondary' : 'success'}}"></i>
                                <span class="text-dark text-xs"> @lang('projects.notifications.telegram.group') </span>
                            </span>

                            <span class="badge badge-dot">
                                <i class="bg-{{$project->telegram_private_ids->isEmpty() ? 'secondary' : 'success'}}"></i>
                                <span class="text-dark text-xs"> @lang('projects.notifications.telegram.private') </span>
                            </span>
                        </td>
                        <td>
                            <span class="text-{{$project->settings['telegram']['enabled'] ? 'info' : 'secondary'}}">
                                @foreach ($project->settings['telegram']['fields'] as $field)
                                    {{ trans('projects.journal.' . $field)  . ($loop->last ? '' : ', ') }}
                                @endforeach
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>