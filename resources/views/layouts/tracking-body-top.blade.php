{{-- ══════ GTM NOSCRIPT FALLBACK ══════ --}}
@if($googleSetting->gtm_enabled && $googleSetting->gtm_container_id)
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $googleSetting->gtm_container_id }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

{{-- ══════ META PIXEL NOSCRIPT FALLBACK ══════ --}}
@if($googleSetting->meta_enabled && $googleSetting->meta_pixel_id)
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{ $googleSetting->meta_pixel_id }}&ev=PageView&noscript=1"
/></noscript>
@endif

{{-- ══════ CUSTOM BODY-TOP SCRIPTS ══════ --}}
@if($googleSetting->custom_body_top_scripts)
{!! $googleSetting->custom_body_top_scripts !!}
@endif