{{-- ══════ GOOGLE TAG MANAGER ══════ --}}
@if($googleSetting->gtm_enabled && $googleSetting->gtm_container_id && ($googleSetting->gtm_inject_position ?? 'head') === 'head')
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl
@if($googleSetting->gtm_auth)+'&gtm_auth={{ $googleSetting->gtm_auth }}&gtm_preview=env-1&gtm_cookies_win=x'@endif
;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $googleSetting->gtm_container_id }}');</script>
@endif

{{-- ══════ GOOGLE ANALYTICS 4 ══════ --}}
@if($googleSetting->ga4_enabled && $googleSetting->ga4_measurement_id)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleSetting->ga4_measurement_id }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ $googleSetting->ga4_measurement_id }}');
</script>
@endif

{{-- ══════ GOOGLE ADS ══════ --}}
@if($googleSetting->gads_enabled && $googleSetting->gads_conversion_id)
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-{{ $googleSetting->gads_conversion_id }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'AW-{{ $googleSetting->gads_conversion_id }}');
</script>
@endif

{{-- ══════ GOOGLE SEARCH CONSOLE — META TAG VERIFICATION ══════ --}}
@if($googleSetting->gsc_verify_method == 'meta' && $googleSetting->gsc_meta_content)
<meta name="google-site-verification" content="{{ $googleSetting->gsc_meta_content }}" />
@endif

{{-- ══════ META DOMAIN VERIFICATION ══════ --}}
@if($googleSetting->meta_domain_verify)
<meta name="facebook-domain-verification" content="{{ $googleSetting->meta_domain_verify }}" />
@endif

{{-- ══════ META PIXEL ══════ --}}
@if($googleSetting->meta_enabled && $googleSetting->meta_pixel_id)
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ $googleSetting->meta_pixel_id }}');
@if($googleSetting->meta_ev_page_view)
fbq('track', 'PageView');
@endif
</script>
<!-- End Meta Pixel Code -->
@endif

{{-- ══════ CUSTOM HEAD SCRIPTS ══════ --}}
@if($googleSetting->custom_head_scripts)
{!! $googleSetting->custom_head_scripts !!}
@endif