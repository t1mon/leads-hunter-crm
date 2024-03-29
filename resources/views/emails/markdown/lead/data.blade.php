@php
    $fields = $lead->project->settings['email']['fields'];
@endphp


@component('mail::layout')
    {{-- Header --}}
    @slot('header')
            @if($type === 'markdown')
                @component('mail::header', ['url' => config('app.url')])
                    @if( File::exists( public_path().'/media/img/logo/mail-logo.png') )
                        <img style="max-height: 200px" src="{{asset('media/img/logo/mail-logo.png')}}">
                    @else
                        {{ config('app.name') }}
                    @endif
                @endcomponent
            @endif
    @endslot

    @if($type === 'markdown')
     @lang('leads.email.data')
    @endif

    @component('mail::panel')
        Имя: <b>{{ $lead->name }}</b><br>
        Телефон: <b>{{ phone_format($lead->phone) }}</b><br>
        @if(count($fields) > 0)
            @foreach($fields as $field)
                @if(!is_null($lead->$field))
                    @lang('projects.notifications.webhooks.common.fields.' . $field): <b>{{$lead->$field}}</b> <br>
                @endif
            @endforeach
        @endif
    @endcomponent

    {{-- Subcopy --}}
    @if($type === 'markdown')
        @slot('subcopy')
            @component('mail::subcopy')
                @lang('leads.email.description')
            @endcomponent
        @endslot
    @endif

    {{-- Footer --}}
    @slot('footer')
        @if($type === 'markdown')
            @component('mail::footer')
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            @endcomponent
        @endif
    @endslot
@endcomponent
