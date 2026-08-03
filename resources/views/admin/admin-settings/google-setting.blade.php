<form action="{{ route('admin.admin-setting.google-setting') }}" method="POST">
    @csrf

    <div class="settings-layout">

        <!-- ── Section Sidenav ── -->
        <div class="settings-sidenav">
            <span class="settings-sidenav-label">Sections</span>
            <a href="#gs-gtm" class="gs-nav active"><i class="fa-brands fa-google"></i> Tag Manager</a>
            <a href="#gs-ga4" class="gs-nav"><i class="fa-solid fa-chart-line"></i> Google Analytics</a>
            <a href="#gs-ads" class="gs-nav"><i class="fa-solid fa-rectangle-ad"></i> Google Ads</a>
            <a href="#gs-search" class="gs-nav"><i class="fa-solid fa-magnifying-glass"></i> Search Console</a>
            <a href="#gs-meta" class="gs-nav"><i class="fa-brands fa-meta"></i> Meta / Facebook</a>
            <a href="#gs-other" class="gs-nav"><i class="fa-solid fa-code"></i> Custom Scripts</a>
        </div>

        <!-- ── Content ── -->
        <div class="settings-content">

            @if(session('success'))
                <div class="info-banner green" style="margin-bottom:20px">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!-- ════════════════════════════
                 1. GOOGLE TAG MANAGER
            ════════════════════════════ -->
            <div class="settings-section" id="gs-gtm">

                <div class="gs-platform-header gtm-header">
                    <div class="gs-platform-icon">
                        <i class="fa-brands fa-google"></i>
                    </div>
                    <div>
                        <div class="gs-platform-name">Google Tag Manager</div>
                        <div class="gs-platform-desc">Deploy and manage all your tracking tags from one place — no code deploys needed.</div>
                    </div>
                    <label class="toggle-switch" style="margin-left:auto;flex-shrink:0">
                        <input type="checkbox" name="gtm_enabled" {{ old('gtm_enabled', $google_setting->gtm_enabled ?? false) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div class="info-banner blue">
                    <i class="fa-solid fa-circle-info"></i>
                    <div>
                        Get your Container ID from <a href="https://tagmanager.google.com" target="_blank" style="color:#0069d9;font-weight:600">tagmanager.google.com</a> → Admin → Container Settings. It looks like <strong>GTM-XXXXXX</strong>.
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field-group">
                        <label class="field-label">Container ID <span class="req">*</span></label>
                        <input type="text" name="gtm_container_id" class="field-input monospace"
                            value="{{ old('gtm_container_id', $google_setting->gtm_container_id ?? '') }}"
                            placeholder="GTM-XXXXXX">
                        <span class="field-hint">Your GTM container ID (e.g. GTM-A1B2C3).</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Inject Position</label>
                        <select name="gtm_inject_position" class="field-select">
                            <option value="head" {{ old('gtm_inject_position', $google_setting->gtm_inject_position ?? 'head') == 'head' ? 'selected' : '' }}>
                                &lt;head&gt; — Recommended
                            </option>
                            <option value="body" {{ old('gtm_inject_position', $google_setting->gtm_inject_position ?? '') == 'body' ? 'selected' : '' }}>
                                &lt;body&gt; — Fallback noscript only
                            </option>
                        </select>
                        <span class="field-hint">GTM requires both a head snippet and a body noscript fallback.</span>
                    </div>
                    <div class="field-group col-full">
                        <label class="field-label">Environments (optional)</label>
                        <div class="input-wrap">
                            <span class="input-prefix">gtm_auth=</span>
                            <input type="text" name="gtm_auth" class="field-input monospace"
                                value="{{ old('gtm_auth', $google_setting->gtm_auth ?? '') }}"
                                placeholder="xxxxxxxxxxxxxxxxxxxx">
                        </div>
                        <span class="field-hint">Only needed if you use GTM Environments (staging/production isolation).</span>
                    </div>
                </div>

                <!-- Live script preview -->
                <div class="code-preview-block" id="gtmPreviewBlock">
                    <div class="code-preview-label"><i class="fa-solid fa-code"></i> Generated Script Preview</div>
                    <pre class="code-preview" id="gtmPreviewCode">&lt;!-- Google Tag Manager --&gt;
&lt;script&gt;(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&amp;l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<span id="gtmIdInPreview">{{ $google_setting->gtm_container_id ?? 'GTM-XXXXXX' }}</span>');&lt;/script&gt;
&lt;!-- End Google Tag Manager --&gt;</pre>
                </div>

                <div class="toggle-row" style="margin-top:16px">
                    <div>
                        <div class="toggle-info-label">Fire on all pages</div>
                        <div class="toggle-info-sub">Load GTM snippet on every page of the storefront.</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="gtm_all_pages" {{ old('gtm_all_pages', $google_setting->gtm_all_pages ?? true) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <div>
                        <div class="toggle-info-label">DataLayer — Push order events</div>
                        <div class="toggle-info-sub">Push purchase, add-to-cart, checkout events into dataLayer automatically.</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="gtm_datalayer_events" {{ old('gtm_datalayer_events', $google_setting->gtm_datalayer_events ?? true) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

            </div>

            <hr class="section-divider">

            <!-- ════════════════════════════
                 2. GOOGLE ANALYTICS 4
            ════════════════════════════ -->
            <div class="settings-section" id="gs-ga4">

                <div class="gs-platform-header ga4-header">
                    <div class="gs-platform-icon" style="background:linear-gradient(135deg,#e37400,#f9ab00)">
                        <i class="fa-solid fa-chart-line" style="color:#fff"></i>
                    </div>
                    <div>
                        <div class="gs-platform-name">Google Analytics 4</div>
                        <div class="gs-platform-desc">Track traffic, conversions, revenue, and user behaviour across your store.</div>
                    </div>
                    <label class="toggle-switch" style="margin-left:auto;flex-shrink:0">
                        <input type="checkbox" name="ga4_enabled" {{ old('ga4_enabled', $google_setting->ga4_enabled ?? false) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div class="info-banner amber">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Already using GTM?</strong> If GTM is enabled above, add GA4 as a tag inside Tag Manager instead of entering a Measurement ID here — otherwise GA4 will fire twice.
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field-group">
                        <label class="field-label">Measurement ID <span class="req">*</span></label>
                        <input type="text" name="ga4_measurement_id" class="field-input monospace"
                            value="{{ old('ga4_measurement_id', $google_setting->ga4_measurement_id ?? '') }}"
                            placeholder="G-XXXXXXXXXX">
                        <span class="field-hint">Found in GA4 → Admin → Data Streams → your stream.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Property ID</label>
                        <input type="text" name="ga4_property_id" class="field-input monospace"
                            value="{{ old('ga4_property_id', $google_setting->ga4_property_id ?? '') }}"
                            placeholder="123456789">
                        <span class="field-hint">Numeric property ID — needed for Reporting API access.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">API Secret (Measurement Protocol)</label>
                        <div style="position:relative">
                            <input type="password" id="ga4Secret" name="ga4_api_secret" class="field-input monospace"
                                value="{{ old('ga4_api_secret', $google_setting->ga4_api_secret ?? '') }}"
                                placeholder="••••••••••••••••••••">
                            <button type="button" onclick="togglePass('ga4Secret',this)"
                                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-hint)">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <span class="field-hint">Required for server-side event tracking via Measurement Protocol.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Client ID Cookie Name</label>
                        <input type="text" name="ga4_client_id_cookie" class="field-input monospace"
                            value="{{ old('ga4_client_id_cookie', $google_setting->ga4_client_id_cookie ?? '_ga') }}"
                            placeholder="_ga">
                        <span class="field-hint">Cookie that stores the GA4 client ID. Default is <code>_ga</code>.</span>
                    </div>
                </div>

                <p class="settings-section-title" style="margin-top:20px;font-size:13px"><i class="fa-solid fa-bolt"></i> Enhanced E-commerce Events</p>
                <p class="settings-section-desc">Choose which GA4 e-commerce events fire automatically on your store.</p>

                <div class="events-grid">
                  @php
    $ga4Events = [
        ['key'=>'ga4_ev_view_item',       'label'=>'view_item',          'desc'=>'Product detail page viewed'],
        ['key'=>'ga4_ev_add_to_cart',     'label'=>'add_to_cart',         'desc'=>'Item added to cart'],
        ['key'=>'ga4_ev_remove_from_cart','label'=>'remove_from_cart',    'desc'=>'Item removed from cart'],
        ['key'=>'ga4_ev_begin_checkout',  'label'=>'begin_checkout',      'desc'=>'Checkout started'],
        ['key'=>'ga4_ev_purchase',        'label'=>'purchase',            'desc'=>'Order successfully placed'],
        ['key'=>'ga4_ev_refund',          'label'=>'refund',              'desc'=>'Refund issued on an order'],
        ['key'=>'ga4_ev_login',           'label'=>'login',               'desc'=>'Customer logged in'],
        ['key'=>'ga4_ev_sign_up',         'label'=>'sign_up',             'desc'=>'New customer registered'],
    ];
@endphp
                    @foreach($ga4Events as $ev)
                    <div class="event-chip">
                        <label class="toggle-switch" style="width:32px;height:18px">
                            <input type="checkbox" name="{{ $ev['key'] }}"
                                {{ old($ev['key'], $google_setting->{$ev['key']} ?? true) ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                        <div>
                            <div class="event-chip-name"><code>{{ $ev['label'] }}</code></div>
                            <div class="event-chip-desc">{{ $ev['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            <hr class="section-divider">

            <!-- ════════════════════════════
                 3. GOOGLE ADS
            ════════════════════════════ -->
            <div class="settings-section" id="gs-ads">

                <div class="gs-platform-header" style="background:linear-gradient(135deg,#1a73e820,#1a73e808);border:1px solid #1a73e830;border-radius:var(--radius-sm);padding:16px 20px;display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div class="gs-platform-icon" style="background:linear-gradient(135deg,#4285f4,#1a73e8)">
                        <i class="fa-solid fa-rectangle-ad" style="color:#fff"></i>
                    </div>
                    <div>
                        <div class="gs-platform-name">Google Ads</div>
                        <div class="gs-platform-desc">Track ad conversions and enable remarketing audiences for your campaigns.</div>
                    </div>
                    <label class="toggle-switch" style="margin-left:auto;flex-shrink:0">
                        <input type="checkbox" name="gads_enabled" {{ old('gads_enabled', $google_setting->gads_enabled ?? false) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div class="form-grid">
                    <div class="field-group">
                        <label class="field-label">Conversion ID <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-prefix">AW-</span>
                            <input type="text" name="gads_conversion_id" class="field-input monospace"
                                value="{{ old('gads_conversion_id', $google_setting->gads_conversion_id ?? '') }}"
                                placeholder="123456789">
                        </div>
                        <span class="field-hint">Found in Google Ads → Tools → Conversion Tracking → your conversion.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Purchase Conversion Label <span class="req">*</span></label>
                        <input type="text" name="gads_purchase_label" class="field-input monospace"
                            value="{{ old('gads_purchase_label', $google_setting->gads_purchase_label ?? '') }}"
                            placeholder="AbCdEfGhIj0K">
                        <span class="field-hint">Label for the purchase conversion action.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Signup Conversion Label</label>
                        <input type="text" name="gads_signup_label" class="field-input monospace"
                            value="{{ old('gads_signup_label', $google_setting->gads_signup_label ?? '') }}"
                            placeholder="AbCdEfGhIj0K">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Add-to-Cart Conversion Label</label>
                        <input type="text" name="gads_cart_label" class="field-input monospace"
                            value="{{ old('gads_cart_label', $google_setting->gads_cart_label ?? '') }}"
                            placeholder="AbCdEfGhIj0K">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Remarketing Tag ID</label>
                        <div class="input-wrap">
                            <span class="input-prefix">AW-</span>
                            <input type="text" name="gads_remarketing_id" class="field-input monospace"
                                value="{{ old('gads_remarketing_id', $google_setting->gads_remarketing_id ?? '') }}"
                                placeholder="123456789">
                        </div>
                        <span class="field-hint">For Dynamic Remarketing — shows past visitors ads with products they viewed.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Currency Code</label>
                        <select name="gads_currency" class="field-select">
                            @php $savedCurrency = old('gads_currency', $google_setting->gads_currency ?? 'INR'); @endphp
                            @foreach(['INR'=>'INR — Indian Rupee','USD'=>'USD — US Dollar','EUR'=>'EUR — Euro','GBP'=>'GBP — British Pound','AED'=>'AED — UAE Dirham'] as $code=>$label)
                                <option value="{{ $code }}" {{ $savedCurrency==$code?'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="toggle-row" style="margin-top:12px">
                    <div>
                        <div class="toggle-info-label">Enhanced Conversions</div>
                        <div class="toggle-info-sub">Send hashed customer data (email, phone) to improve conversion matching accuracy.</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="gads_enhanced_conversions" {{ old('gads_enhanced_conversions', $google_setting->gads_enhanced_conversions ?? false) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div class="toggle-row">
                    <div>
                        <div class="toggle-info-label">Auto-populate Transaction Value</div>
                        <div class="toggle-info-sub">Pass actual order total to Google Ads on every purchase event.</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="gads_send_order_value" {{ old('gads_send_order_value', $google_setting->gads_send_order_value ?? true) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

            </div>

            <hr class="section-divider">

            <!-- ════════════════════════════
                 4. GOOGLE SEARCH CONSOLE
            ════════════════════════════ -->
            <div class="settings-section" id="gs-search">

                <div class="settings-section-title">
                    <i class="fa-solid fa-magnifying-glass" style="color:#34a853"></i> Google Search Console
                </div>
                <p class="settings-section-desc">
                    Verify site ownership to access search performance, indexing status, and crawl reports.
                </p>

                <div class="form-grid">
                    <div class="field-group col-full">
                        <label class="field-label">Verification Method</label>
                        <select name="gsc_verify_method" id="gscMethod" class="field-select" onchange="toggleGscMethod(this.value)">
                            <option value="">— Choose Method —</option>
                            <option value="meta"    {{ old('gsc_verify_method', $google_setting->gsc_verify_method ?? '') == 'meta'    ? 'selected' : '' }}>HTML Meta Tag</option>
                            <option value="file"    {{ old('gsc_verify_method', $google_setting->gsc_verify_method ?? '') == 'file'    ? 'selected' : '' }}>HTML File Upload (manual)</option>
                            <option value="dns"     {{ old('gsc_verify_method', $google_setting->gsc_verify_method ?? '') == 'dns'     ? 'selected' : '' }}>DNS TXT Record (manual)</option>
                        </select>
                    </div>

                    <!-- Meta Tag method -->
                    <div class="field-group col-full gsc-method-field" id="gsc-meta" style="display:none">
                        <label class="field-label">Meta Tag Content Value <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-prefix">content=</span>
                            <input type="text" name="gsc_meta_content" class="field-input monospace"
                                value="{{ old('gsc_meta_content', $google_setting->gsc_meta_content ?? '') }}"
                                placeholder="abc123xyz...">
                        </div>
                        <span class="field-hint">Paste only the <code>content</code> value from the meta tag Google gives you. We inject it automatically.</span>
                        <div class="code-preview-block" style="margin-top:12px">
                            <div class="code-preview-label"><i class="fa-solid fa-code"></i> Auto-injected in &lt;head&gt;</div>
                            <pre class="code-preview">&lt;meta name="google-site-verification" content="<span id="gscMetaPreview">{{ $google_setting->gsc_meta_content ?? 'YOUR_VALUE' }}</span>" /&gt;</pre>
                        </div>
                    </div>

                    <!-- DNS / File info banners -->
                    <div class="col-full gsc-method-field" id="gsc-file" style="display:none">
                        <div class="info-banner amber">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div>
                                HTML File method requires you to manually upload the verification file to your server's root directory. This platform cannot do that automatically. Use the Meta Tag method for one-click verification.
                            </div>
                        </div>
                    </div>

                    <div class="col-full gsc-method-field" id="gsc-dns" style="display:none">
                        <div class="info-banner amber">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div>
                                DNS TXT Record must be added via your domain registrar or DNS provider. This platform does not manage DNS records. Copy the TXT value from Google Search Console and add it to your DNS manually.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="toggle-row" style="margin-top:4px">
                    <div>
                        <div class="toggle-info-label">Submit Sitemap Automatically</div>
                        <div class="toggle-info-sub">Ping Google Search Console with your sitemap URL on every publish.</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="gsc_auto_sitemap" {{ old('gsc_auto_sitemap', $google_setting->gsc_auto_sitemap ?? false) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div class="form-grid" style="margin-top:12px">
                    <div class="field-group col-full">
                        <label class="field-label">Sitemap URL</label>
                        <div class="input-wrap">
                            <span class="input-prefix">{{ url('/') }}/</span>
                            <input type="text" name="gsc_sitemap_path" class="field-input monospace"
                                value="{{ old('gsc_sitemap_path', $google_setting->gsc_sitemap_path ?? 'sitemap.xml') }}"
                                placeholder="sitemap.xml">
                        </div>
                    </div>
                </div>

            </div>

            <hr class="section-divider">

            <!-- ════════════════════════════
                 5. META / FACEBOOK PIXEL
            ════════════════════════════ -->
            <div class="settings-section" id="gs-meta">

                <div class="gs-platform-header" style="background:linear-gradient(135deg,#0866ff20,#0866ff08);border:1px solid #0866ff30;border-radius:var(--radius-sm);padding:16px 20px;display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div class="gs-platform-icon" style="background:linear-gradient(135deg,#0866ff,#0078ff)">
                        <i class="fa-brands fa-meta" style="color:#fff"></i>
                    </div>
                    <div>
                        <div class="gs-platform-name">Meta Pixel &amp; Conversions API</div>
                        <div class="gs-platform-desc">Track Facebook / Instagram ad performance, retarget visitors, and build lookalike audiences.</div>
                    </div>
                    <label class="toggle-switch" style="margin-left:auto;flex-shrink:0">
                        <input type="checkbox" name="meta_enabled" {{ old('meta_enabled', $google_setting->meta_enabled ?? false) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div class="form-grid">
                    <div class="field-group">
                        <label class="field-label">Pixel ID <span class="req">*</span></label>
                        <input type="text" name="meta_pixel_id" class="field-input monospace"
                            value="{{ old('meta_pixel_id', $google_setting->meta_pixel_id ?? '') }}"
                            placeholder="1234567890123456">
                        <span class="field-hint">Events Manager → your Pixel → Settings → Pixel ID.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Access Token (CAPI) <span class="req">*</span></label>
                        <div style="position:relative">
                            <input type="password" id="metaCapi" name="meta_capi_token" class="field-input monospace"
                                value="{{ old('meta_capi_token', $google_setting->meta_capi_token ?? '') }}"
                                placeholder="EAAxxxxxxxxxxxxxxx">
                            <button type="button" onclick="togglePass('metaCapi',this)"
                                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-hint)">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <span class="field-hint">Conversions API token — enables server-side event tracking.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Test Event Code</label>
                        <input type="text" name="meta_test_event_code" class="field-input monospace"
                            value="{{ old('meta_test_event_code', $google_setting->meta_test_event_code ?? '') }}"
                            placeholder="TEST12345">
                        <span class="field-hint">Only for testing CAPI events. Remove before going live.</span>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Meta Domain Verification</label>
                        <input type="text" name="meta_domain_verify" class="field-input monospace"
                            value="{{ old('meta_domain_verify', $google_setting->meta_domain_verify ?? '') }}"
                            placeholder="abc123def456">
                        <span class="field-hint">Content value for <code>&lt;meta name="facebook-domain-verification"&gt;</code>.</span>
                    </div>
                </div>

                <p class="settings-section-title" style="margin-top:20px;font-size:13px"><i class="fa-solid fa-bolt"></i> Standard Events</p>
                <p class="settings-section-desc">Choose which Meta standard events fire automatically.</p>

                <div class="events-grid">
                   @php
    $metaEvents = [
        ['key'=>'meta_ev_page_view',      'label'=>'PageView',          'desc'=>'Every page load'],
        ['key'=>'meta_ev_view_content',   'label'=>'ViewContent',       'desc'=>'Product page viewed'],
        ['key'=>'meta_ev_add_to_cart',    'label'=>'AddToCart',         'desc'=>'Item added to cart'],
        ['key'=>'meta_ev_add_to_wishlist','label'=>'AddToWishlist',     'desc'=>'Item wishlisted'],
        ['key'=>'meta_ev_initiate_checkout','label'=>'InitiateCheckout','desc'=>'Checkout started'],
        ['key'=>'meta_ev_purchase',       'label'=>'Purchase',          'desc'=>'Order placed successfully'],
        ['key'=>'meta_ev_lead',           'label'=>'Lead',              'desc'=>'Contact / enquiry form submitted'],
        ['key'=>'meta_ev_complete_reg',   'label'=>'CompleteRegistration','desc'=>'New customer registered'],
    ];
@endphp
                    @foreach($metaEvents as $ev)
                    <div class="event-chip">
                        <label class="toggle-switch" style="width:32px;height:18px">
                            <input type="checkbox" name="{{ $ev['key'] }}"
                                {{ old($ev['key'], $google_setting->{$ev['key']} ?? true) ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                        <div>
                            <div class="event-chip-name"><code>{{ $ev['label'] }}</code></div>
                            <div class="event-chip-desc">{{ $ev['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="toggle-row" style="margin-top:16px">
                    <div>
                        <div class="toggle-info-label">Advanced Matching</div>
                        <div class="toggle-info-sub">Send hashed customer email / phone to improve audience matching on Meta.</div>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="meta_advanced_matching" {{ old('meta_advanced_matching', $google_setting->meta_advanced_matching ?? false) ? 'checked' : '' }}>
                        <span class="toggle-track"></span>
                    </label>
                </div>

            </div>

            <hr class="section-divider">

            <!-- ════════════════════════════
                 6. CUSTOM SCRIPTS
            ════════════════════════════ -->
            <div class="settings-section" id="gs-other">

                <div class="settings-section-title">
                    <i class="fa-solid fa-code"></i> Custom Scripts &amp; Head Tags
                </div>
                <p class="settings-section-desc">
                    Paste any third-party script (Hotjar, Clarity, TikTok Pixel, Snapchat, LinkedIn Insight Tag, etc.) directly. Scripts are injected verbatim — wrap in <code>&lt;script&gt;</code> tags where needed.
                </p>

                <div class="info-banner amber" style="margin-bottom:20px">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        Custom scripts run on every page. A broken script can affect storefront performance. Test thoroughly before saving.
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field-group col-full">
                        <label class="field-label">Custom &lt;head&gt; Scripts</label>
                        <textarea name="custom_head_scripts" class="field-textarea monospace" rows="7"
                            placeholder="<!-- Hotjar Tracking Code -->&#10;<script>&#10;    (function(h,o,t,j,a,r){ ... })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');&#10;</script>">{{ old('custom_head_scripts', $google_setting->custom_head_scripts ?? '') }}</textarea>
                        <span class="field-hint">Injected just before <code>&lt;/head&gt;</code> on every storefront page.</span>
                    </div>

                    <div class="field-group col-full">
                        <label class="field-label">Custom &lt;body&gt; Scripts (top)</label>
                        <textarea name="custom_body_top_scripts" class="field-textarea monospace" rows="5"
                            placeholder="<!-- Scripts injected right after <body> opens -->">{{ old('custom_body_top_scripts', $google_setting->custom_body_top_scripts ?? '') }}</textarea>
                        <span class="field-hint">Injected right after <code>&lt;body&gt;</code> opens. Use for GTM noscript fallback if needed.</span>
                    </div>

                    <div class="field-group col-full">
                        <label class="field-label">Custom &lt;/body&gt; Scripts (bottom)</label>
                        <textarea name="custom_body_bottom_scripts" class="field-textarea monospace" rows="5"
                            placeholder="<!-- Scripts injected just before </body> closes -->">{{ old('custom_body_bottom_scripts', $google_setting->custom_body_bottom_scripts ?? '') }}</textarea>
                        <span class="field-hint">Injected just before <code>&lt;/body&gt;</code> closes. Good for less critical third-party widgets.</span>
                    </div>

                    <!-- Quick-add chips for common platforms -->
                    <div class="field-group col-full">
                        <label class="field-label">Quick Add — Common Platforms</label>
                        <div class="quick-add-grid">
                            <button type="button" class="quick-add-chip" onclick="quickAddScript('hotjar')">
                                <i class="fa-solid fa-fire"></i> Hotjar
                            </button>
                            <button type="button" class="quick-add-chip" onclick="quickAddScript('clarity')">
                                <i class="fa-brands fa-microsoft"></i> MS Clarity
                            </button>
                            <button type="button" class="quick-add-chip" onclick="quickAddScript('tiktok')">
                                <i class="fa-brands fa-tiktok"></i> TikTok Pixel
                            </button>
                            <button type="button" class="quick-add-chip" onclick="quickAddScript('snapchat')">
                                <i class="fa-brands fa-snapchat"></i> Snapchat
                            </button>
                            <button type="button" class="quick-add-chip" onclick="quickAddScript('linkedin')">
                                <i class="fa-brands fa-linkedin"></i> LinkedIn
                            </button>
                            <button type="button" class="quick-add-chip" onclick="quickAddScript('pinterest')">
                                <i class="fa-brands fa-pinterest"></i> Pinterest
                            </button>
                        </div>
                        <span class="field-hint">Clicking a chip inserts a placeholder snippet into the Head Scripts box above — fill in your Pixel/Site ID.</span>
                    </div>
                </div>

            </div>

        </div><!-- /settings-content -->
    </div><!-- /settings-layout -->

    <!-- Action bar -->
    <div class="action-bar">
        <button type="button" class="btn-test" onclick="verifyPixels()">
            <i class="fa fa-vial"></i> Verify Tags
        </button>
        <a href="{{ route('admin.admin-setting.index', ['tab' => 'tracking']) }}" class="btn-secondary-dash">
            Discard Changes
        </a>
        <button type="submit" class="btn-primary-dash">
            <i class="fa fa-save"></i> Save Tracking Settings
        </button>
    </div>

</form>

<!-- ── Google Setting Page — Scoped Styles ── -->
<style>
    .gs-platform-header {
        display: flex;
        align-items: center;
        gap: 14px;
        background: linear-gradient(135deg, #303d8914, #303d8906);
        border: 1px solid rgba(48, 61, 137, .15);
        border-radius: var(--radius-sm);
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .gs-platform-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #303d89, #4f5db3);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
        color: #fff;
    }

    .gs-platform-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .gs-platform-desc {
        font-size: 12px;
        color: var(--text-hint);
        margin-top: 2px;
    }

    .code-preview-block {
        background: #0d1117;
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-top: 14px;
    }

    .code-preview-label {
        background: #161b22;
        padding: 8px 14px;
        font-size: 11px;
        font-weight: 600;
        color: #8b949e;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .code-preview {
        margin: 0;
        padding: 14px 16px;
        font-family: 'SF Mono', 'Fira Mono', 'Consolas', monospace;
        font-size: 11.5px;
        line-height: 1.7;
        color: #e6edf3;
        white-space: pre-wrap;
        word-break: break-all;
        overflow-x: auto;
    }

    .code-preview #gtmIdInPreview,
    .code-preview #gscMetaPreview {
        color: #79c0ff;
        font-weight: 700;
    }

    .events-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 4px;
    }

    @media(max-width:640px) {
        .events-grid {
            grid-template-columns: 1fr;
        }
    }

    .event-chip {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 10px 12px;
    }

    .event-chip-name code {
        font-size: 12px;
        font-family: 'SF Mono', 'Fira Mono', monospace;
        font-weight: 600;
        color: var(--accent);
        background: var(--accent-light);
        padding: 1px 6px;
        border-radius: 4px;
    }

    .event-chip-desc {
        font-size: 11px;
        color: var(--text-hint);
        margin-top: 3px;
    }

    .quick-add-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 6px;
    }

    .quick-add-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
        font-family: var(--font);
        transition: all .13s;
    }

    .quick-add-chip:hover {
        background: var(--accent-light);
        border-color: var(--accent);
        color: var(--accent);
    }

    .events-grid .toggle-track::after {
        width: 12px;
        height: 12px;
        top: 3px;
        left: 3px;
    }

    .events-grid .toggle-switch input:checked + .toggle-track::after {
        transform: translateX(14px);
    }
</style>

<script>
    // ── Sidenav highlight on section scroll ──
    (function () {
        const navLinks = document.querySelectorAll('.gs-nav');
        const sections = Array.from(navLinks).map(a => document.querySelector(a.getAttribute('href')));

        function onScroll() {
            const scrollY = window.scrollY || document.documentElement.scrollTop;
            let active = 0;
            sections.forEach((sec, i) => {
                if (sec && sec.getBoundingClientRect().top <= 120) active = i;
            });
            navLinks.forEach((a, i) => {
                a.classList.toggle('active', i === active);
            });
        }

        window.addEventListener('scroll', onScroll, { passive: true });
    })();

    // ── GTM live ID preview ──
    const gtmInput = document.querySelector('[name="gtm_container_id"]');
    if (gtmInput) {
        gtmInput.addEventListener('input', function () {
            const el = document.getElementById('gtmIdInPreview');
            if (el) el.textContent = this.value || 'GTM-XXXXXX';
        });
    }

    // ── GSC meta preview ──
    const gscInput = document.querySelector('[name="gsc_meta_content"]');
    if (gscInput) {
        gscInput.addEventListener('input', function () {
            const el = document.getElementById('gscMetaPreview');
            if (el) el.textContent = this.value || 'YOUR_VALUE';
        });
    }

    // ── GSC method toggle ──
    function toggleGscMethod(val) {
        document.querySelectorAll('.gsc-method-field').forEach(el => el.style.display = 'none');
        if (val) {
            const target = document.getElementById('gsc-' + val);
            if (target) target.style.display = 'block';
        }
    }
    // Init
    (function () {
        const sel = document.getElementById('gscMethod');
        if (sel && sel.value) toggleGscMethod(sel.value);
    })();

    // ── Quick add snippet stubs ──
    const snippets = {
        hotjar: `<!-- Hotjar Tracking Code — replace SITE_ID -->\n<script>\n    (function(h,o,t,j,a,r){\n        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};\n        h._hjSettings={hjid:SITE_ID,hjsv:6};\n        a=o.getElementsByTagName('head')[0];\n        r=o.createElement('script');r.async=1;\n        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;\n        a.appendChild(r);\n    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');\n<\/script>`,
        clarity: `<!-- Microsoft Clarity — replace PROJECT_ID -->\n<script type="text/javascript">\n    (function(c,l,a,r,i,t,y){\n        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};\n        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;\n        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);\n    })(window, document, "clarity", "script", "PROJECT_ID");\n<\/script>`,
        tiktok: `<!-- TikTok Pixel — replace PIXEL_ID -->\n<script>\n    !function (w, d, t) {\n        w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];\n        ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];\n        ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};\n        for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);\n        ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e};\n        ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";\n        ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};\n        var o=document.createElement("script");o.type="text/javascript";o.async=!0;o.src=i+"?sdkid="+e+"&lib="+t;\n        var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};\n        ttq.load('PIXEL_ID');ttq.page();\n    }(window, document, 'ttq');\n<\/script>`,
        snapchat: `<!-- Snapchat Pixel — replace PIXEL_ID -->\n<script type='text/javascript'>\n    (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function(){a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};\n    a.queue=[];var s='script';r=t.createElement(s);r.async=!0;r.src=n;\n    var u=t.getElementsByTagName(s)[0];u.parentNode.insertBefore(r,u);\n    })(window,document,'https://sc-static.net/scevent.min.js');\n    snaptr('init', 'PIXEL_ID', {'user_email': '__INSERT_USER_EMAIL__'});\n    snaptr('track', 'PAGE_VIEW');\n<\/script>`,
        linkedin: `<!-- LinkedIn Insight Tag — replace PARTNER_ID -->\n<script type="text/javascript">\n    _linkedin_partner_id = "PARTNER_ID";\n    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];\n    window._linkedin_data_partner_ids.push(_linkedin_partner_id);\n<\/script>\n<script type="text/javascript">\n    (function(l) {\n        if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};\n        window.lintrk.q=[]}\n        var s = document.getElementsByTagName("script")[0];\n        var b = document.createElement("script");\n        b.type = "text/javascript";b.async = true;\n        b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";\n        s.parentNode.insertBefore(b, s);})(window.lintrk);\n<\/script>`,
        pinterest: `<!-- Pinterest Tag — replace PINTEREST_TAG_ID -->\n<script>\n    !function(e){if(!window.pintrk){window.pintrk = function () {\n        window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var n=window.pintrk;\n        n.queue=[],n.version="3.0";var t=document.createElement("script");\n        t.async=!0,t.src=e;var r=document.getElementsByTagName("script")[0];\n        r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");\n    pintrk('load', 'PINTEREST_TAG_ID');\n    pintrk('page');\n<\/script>`,
    };

    function quickAddScript(type) {
        const ta = document.querySelector('[name="custom_head_scripts"]');
        if (!ta) return;
        const snippet = snippets[type] || '';
        ta.value = ta.value ? ta.value + '\n\n' + snippet : snippet;
        ta.focus();
        ta.scrollTop = ta.scrollHeight;
    }

    // ── Verify pixels helper ──
    function verifyPixels() {
        const checks = [];
        const gtmId  = document.querySelector('[name="gtm_container_id"]')?.value;
        const ga4Id  = document.querySelector('[name="ga4_measurement_id"]')?.value;
        const adsId  = document.querySelector('[name="gads_conversion_id"]')?.value;
        const pixelId = document.querySelector('[name="meta_pixel_id"]')?.value;

        if (gtmId)   checks.push(`✔ GTM Container: <strong>${gtmId}</strong>`);
        if (ga4Id)   checks.push(`✔ GA4 Measurement ID: <strong>${ga4Id}</strong>`);
        if (adsId)   checks.push(`✔ Google Ads Conversion ID: <strong>AW-${adsId}</strong>`);
        if (pixelId) checks.push(`✔ Meta Pixel ID: <strong>${pixelId}</strong>`);

        const msg = checks.length
            ? `The following IDs are configured:<br><br>${checks.join('<br>')}<br><br>Save settings and use your browser's extension (Tag Assistant / Meta Pixel Helper) to verify live firing.`
            : 'No tracking IDs configured yet. Add at least one ID to verify.';

        Swal.fire({
            icon: checks.length ? 'success' : 'warning',
            title: 'Configured Tracking IDs',
            html: msg,
            confirmButtonColor: '#303d89',
        });
    }
</script>