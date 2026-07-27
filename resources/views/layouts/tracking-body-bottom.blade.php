{{-- ══════ CUSTOM BODY-BOTTOM SCRIPTS ══════ --}}
@if($googleSetting->custom_body_bottom_scripts)
{!! $googleSetting->custom_body_bottom_scripts !!}
@endif

@include('layouts.tracking-lead-event')