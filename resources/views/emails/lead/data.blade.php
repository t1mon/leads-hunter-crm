<h1>@lang('leads.email.data')</h1>


<ul>
    <li><b>Имя:</b> {{ $lead->name }} </li>
    <li><b>Телефон:</b> {{ phone_format($lead->phone) }} </li>
</ul>

<p>
    @lang('leads.email.description')
</p>
