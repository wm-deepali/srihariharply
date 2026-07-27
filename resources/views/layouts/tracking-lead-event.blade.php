@if(session('fire_lead_event'))
<script>
    @php
        $leadScript = \App\Services\Tracking\PixelTracker::leadScript(session('fire_lead_event'));
    @endphp
    {!! $leadScript !!}
</script>
@endif

@if(session('fire_signup_event'))
<script>
    @php
        $signUpEvents = \App\Services\Tracking\PixelTracker::signUpEvent(session('fire_signup_event'));
    @endphp
    @if(!empty($signUpEvents['ga4']))
        gtag('event', 'sign_up', {!! json_encode($signUpEvents['ga4']['params']) !!});
    @endif
    @if(!empty($signUpEvents['meta']))
        fbq('track', 'CompleteRegistration', {!! json_encode($signUpEvents['meta']['params']) !!});
    @endif
</script>
@endif

@if(session('fire_login_event'))
<script>
    @php
        $loginEvent = \App\Services\Tracking\PixelTracker::loginEvent();
    @endphp
    @if(!empty($loginEvent['ga4']))
        gtag('event', 'login', {!! json_encode($loginEvent['ga4']['params']) !!});
    @endif
</script>
@endif