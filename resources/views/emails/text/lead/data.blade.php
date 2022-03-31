@php
    $fields = $lead->project->settings['email']['fields'];
@endphp
Имя: {{ $lead->name }}
Телефон: {{ phone_format($lead->phone) }}
@if(count($fields) > 0)
@foreach($fields as $field)
@if(!is_null($lead->$field))
{{trim(__('projects.notifications.webhooks.common.fields.' . $field).': '.$lead->$field)}}
@endif
@endforeach
@endif

