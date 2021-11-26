<div class="card">
    <div class="card-body">
        <h5 class="card-title text-center">@lang('projects.notifications.info.info')</h5>
        <div class="table-responsive mb-3">
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
                        <td class="text-start text-{{$project->settings['email']['enabled'] ? 'success' : 'danger'}}">E-mail</td>
                        <td class="text-center">
                            <span class="badge badge-dot">
                                <i class="bg-{{$project->settings['email']['enabled'] ? 'success' : 'secondary'}}"></i>
                            </span>
                        </td>
                        <td></td>
                        <td class="text-start">
                            <span class="text-{{$project->settings['email']['enabled'] ? 'info' : 'secondary'}}">
                                @foreach ($project->settings['email']['fields'] as $field)
                                    {{ trans('projects.journal.' . $field)  . ($loop->last ? '' : ', ') }}
                                @endforeach
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-start text-{{$project->settings['telegram']['enabled'] ? 'success' : 'danger'}}">Telegram</td>
                        <td class="text-center">
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
                        <td class="text-start">
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

        <div class="table-responsive">
            <h6>@lang('projects.notifications.tab_webhooks')</h6>
            <table class="table text-center align-middle">
                <thead>
                    <th>@lang('projects.notifications.webhooks.name')</th>
                    <th>@lang('projects.notifications.webhooks.types.type')</th>
                    <th>@lang('projects.notifications.info.status')</th>
                    <th>@lang('projects.notifications.webhooks.method')</th>
                    <th>@lang('projects.notifications.webhooks.fields')</th>
                </thead>
                <tbody>
                @isset($project->webhooks)
                    @foreach ($project->webhooks as $webhook)
                        <tr>
                            <td class="text-start text-{{$webhook->enabled ? 'success' : 'danger'}}">{{$webhook->name}}</td>
                            <td class="text-start text-{{$webhook->enabled ? 'success' : 'danger'}}">{{trans("projects.notifications.webhooks.types.$webhook->type")}}</td>
                            <td class="text-center">
                                <span class="badge badge-dot">
                                    <i class="bg-{{$webhook->enabled ? 'success' : 'secondary'}}"></i>
                                </span>
                            </td>
                            <td>
                                <span class="text-{{$webhook->enabled ? 'warning' : 'secondary'}} fw-bold">
                                    {{$webhook->method}}
                                </span>
                            </td>
                            <td class="text-start">
                                <span class="text-{{$webhook->enabled ? 'info' : 'secondary'}}">
                                    @foreach ($webhook->fields as $field)
                                        {{ trans("projects.notifications.webhooks.{$webhook->type}.fields.{$field}")  . ($loop->last ? '' : ', ') }}
                                    @endforeach
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    <tfoot>
                        <tr>
                            <td colspan="5">@lang('projects.notifications.webhooks.total-active'): <b>{{count($project->webhooks_active())}}</b></td>
                        </tr>
                    </tfoot>
                @else
                    <td colspan="4">@lang('projects.notifications.webhooks.none')</td>
                @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
