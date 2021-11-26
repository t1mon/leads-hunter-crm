@php
    $fields = $lead->project->settings['email']['fields'];
@endphp

<h1>@lang('leads.email.data')</h1>

<ul>
    <li><b>Имя:</b> {{ $lead->name }} </li>
    <li><b>Телефон:</b> {{ phone_format($lead->phone) }} </li>

    <!--Вывод остальных полей-->
    @if(count($fields) > 0)
        @foreach($fields as $field)
            <li><b>@lang('projects.journal.' . $field)</b> {{$lead->$field}}</li>
        @endforeach
    @endif

</ul>

<p>
    @lang('leads.email.description')
</p>

