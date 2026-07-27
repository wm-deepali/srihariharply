@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    <style>
        :root {
            --bg: #f1f2f4;
            --surface: #ffffff;
            --border: #e3e5e8;
            --text-primary: #202223;
            --text-secondary: #6d7175;
            --text-hint: #8c9196;
            --accent: #303d89;
            --accent-light: #f0f1fc;
            --green: #007a5e;
            --green-bg: #e3f1ec;
            --red: #b22222;
            --red-bg: #fce8e8;
            --amber: #916a00;
            --amber-bg: #fff5cc;
            --radius-sm: 8px;
            --radius-md: 12px;
            --shadow-card: 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 1px var(--border);
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .settings-page {
            background: var(--bg);
            padding: 24px 28px;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text-primary);
        }

        .settings-page * {
            box-sizing: border-box;
        }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 20px;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0;
        }

        .crumb {
            font-size: 12.5px;
            color: var(--text-hint);
            margin-top: 3px;
        }

        .crumb a {
            color: var(--accent);
            text-decoration: none;
        }

        .crumb a:hover {
            text-decoration: underline;
        }

        .crumb span {
            margin: 0 5px;
        }

        /* ── Buttons ── */
        .btn-primary-dash {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent);
            color: #fff !important;
            border: none;
            border-radius: var(--radius-sm);
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: background .15s;
            box-shadow: 0 1px 3px rgba(48, 61, 137, .25);
        }

        .btn-primary-dash:hover {
            background: #252f70;
        }

        .btn-primary-dash:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .btn-secondary-dash {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--surface);
            color: var(--text-primary) !important;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: background .15s;
        }

        .btn-secondary-dash:hover {
            background: var(--bg);
        }

        .btn-danger-soft {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--red-bg);
            color: var(--red) !important;
            border: 1px solid #f5c0c0;
            border-radius: var(--radius-sm);
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: all .15s;
        }

        .btn-danger-soft:hover {
            background: var(--red);
            color: #fff !important;
        }

        /* ── Tab navigation ── */
        .tab-shell {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .tab-nav {
            display: flex;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .tab-nav::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 22px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
            font-family: var(--font);
            transition: color .15s, border-color .15s;
            position: relative;
        }

        .tab-btn i {
            font-size: 14px;
            color: var(--text-hint);
            transition: color .15s;
        }

        .tab-btn:hover {
            color: var(--text-primary);
        }

        .tab-btn:hover i {
            color: var(--text-secondary);
        }

        .tab-btn.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
            font-weight: 600;
        }

        .tab-btn.active i {
            color: var(--accent);
        }

        /* ── Tab panels ── */
        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        /* ── Two-column settings layout ── */
        .settings-layout {
            display: grid;
            grid-template-columns: 220px 1fr;
            min-height: 600px;
        }

        @media(max-width:860px) {
            .settings-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Settings sidebar (section nav within tab) ── */
        .settings-sidenav {
            border-right: 1px solid var(--border);
            padding: 20px 0;
            background: #fafafa;
        }

        .settings-sidenav-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--text-hint);
            padding: 0 18px 8px;
            display: block;
        }

        .settings-sidenav a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: all .13s;
        }

        .settings-sidenav a i {
            font-size: 12px;
            color: var(--text-hint);
            width: 14px;
            text-align: center;
        }

        .settings-sidenav a:hover {
            color: var(--text-primary);
            background: rgba(48, 61, 137, .04);
        }

        .settings-sidenav a.active {
            color: var(--accent);
            border-left-color: var(--accent);
            background: var(--accent-light);
            font-weight: 600;
        }

        .settings-sidenav a.active i {
            color: var(--accent);
        }

        /* ── Settings content area ── */
        .settings-content {
            padding: 28px 32px;
        }

        @media(max-width:860px) {
            .settings-content {
                padding: 20px;
            }
        }

        /* ── Section block ── */
        .settings-section {
            margin-bottom: 36px;
        }

        .settings-section:last-child {
            margin-bottom: 0;
        }

        .settings-section-title {
            font-size: 14px;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .settings-section-title i {
            font-size: 14px;
            color: var(--accent);
        }

        .settings-section-desc {
            font-size: 12.5px;
            color: var(--text-hint);
            margin: 0 0 18px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 28px 0;
        }

        /* ── Form grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .col-full {
            grid-column: 1 / -1;
        }

        @media(max-width:640px) {

            .form-grid,
            .form-grid-3 {
                grid-template-columns: 1fr;
            }

            .col-full {
                grid-column: 1;
            }
        }

        /* ── Field ── */
        .field-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .field-label .req {
            color: var(--red);
            margin-left: 2px;
        }

        .field-hint {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 2px;
        }

        .field-input,
        .field-select,
        .field-textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 13.5px;
            color: var(--text-primary);
            background: var(--surface);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            font-family: var(--font);
        }

        .field-input,
        .field-select {
            height: 38px;
        }

        .field-textarea {
            padding: 10px 12px;
            resize: vertical;
            min-height: 90px;
        }

        .field-input:focus,
        .field-select:focus,
        .field-textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        .field-input[readonly] {
            background: var(--bg);
            color: var(--text-secondary);
            cursor: not-allowed;
        }

        .field-input.monospace {
            font-family: 'SF Mono', 'Fira Mono', monospace;
            font-size: 13px;
            letter-spacing: .02em;
        }

        /* Input with prefix */
        .input-wrap {
            display: flex;
        }

        .input-prefix {
            display: inline-flex;
            align-items: center;
            padding: 0 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-right: none;
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
            font-size: 12.5px;
            color: var(--text-hint);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .input-wrap .field-input {
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        /* ── Toggle switch ── */
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid var(--bg);
        }

        .toggle-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .toggle-row:first-child {
            padding-top: 0;
        }

        .toggle-info-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .toggle-info-sub {
            font-size: 12px;
            color: var(--text-hint);
            margin-top: 2px;
        }

        .toggle-switch {
            position: relative;
            width: 38px;
            height: 22px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-track {
            position: absolute;
            inset: 0;
            background: var(--border);
            border-radius: 22px;
            cursor: pointer;
            transition: background .2s;
        }

        .toggle-track::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 3px;
            width: 16px;
            height: 16px;
            background: #fff;
            border-radius: 50%;
            transition: transform .2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        }

        .toggle-switch input:checked+.toggle-track {
            background: var(--accent);
        }

        .toggle-switch input:checked+.toggle-track::after {
            transform: translateX(16px);
        }

        /* ── Upload area ── */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius-sm);
            padding: 22px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            position: relative;
        }

        .upload-area:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .upload-icon {
            font-size: 22px;
            color: var(--text-hint);
            margin-bottom: 6px;
        }

        .upload-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .upload-sub {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 2px;
        }

        /* ── Info / warning banners ── */
        .info-banner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 13px;
        }

        .info-banner i {
            font-size: 15px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .info-banner.blue {
            background: #e8f2ff;
            border: 1px solid #b8d4f5;
            color: #0069d9;
        }

        .info-banner.amber {
            background: var(--amber-bg);
            border: 1px solid #f0d060;
            color: var(--amber);
        }

        .info-banner.green {
            background: var(--green-bg);
            border: 1px solid #b0ddd0;
            color: var(--green);
        }

        /* ── API key box ── */
        .api-key-card {
            background: #fafafa;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 16px;
            margin-bottom: 16px;
        }

        .api-key-card-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-hint);
            margin-bottom: 10px;
        }

        /* ── Razorpay branding strip ── */
        .razorpay-header {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #072654 0%, #0d3f8f 100%);
            border-radius: var(--radius-sm);
            padding: 16px 20px;
            margin-bottom: 20px;
        }

        .razorpay-logo {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .razorpay-logo i {
            font-size: 22px;
            color: #072654;
        }

        .razorpay-name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .razorpay-desc {
            font-size: 12px;
            color: rgba(255, 255, 255, .7);
            margin-top: 2px;
        }

        /* ── GST invoice preview strip ── */
        .invoice-preview-bar {
            background: linear-gradient(90deg, #303d89 0%, #4f5db3 100%);
            border-radius: var(--radius-sm);
            padding: 14px 18px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .invoice-preview-bar span {
            font-size: 13px;
            color: rgba(255, 255, 255, .85);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .invoice-preview-bar span strong {
            color: #fff;
        }

        /* ── Serial number preview ── */
        .serial-preview {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
            border: 1px solid rgba(48, 61, 137, .2);
            border-radius: var(--radius-sm);
            padding: 8px 14px;
            margin-top: 10px;
        }

        .serial-preview-label {
            font-size: 12px;
            color: var(--text-hint);
        }

        .serial-preview-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--accent);
            font-family: 'SF Mono', 'Fira Mono', monospace;
        }

        /* ── Action bar (bottom) ── */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 18px 32px;
            border-top: 1px solid var(--border);
            background: #fafafa;
        }

        @media(max-width:860px) {
            .action-bar {
                padding: 14px 20px;
            }
        }

        /* ── Test connection button ── */
        .btn-test {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--amber-bg);
            color: var(--amber) !important;
            border: 1px solid #f0d060;
            border-radius: var(--radius-sm);
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: all .15s;
        }

        .btn-test:hover {
            background: var(--amber);
            color: #fff !important;
        }

        /* ── Mode pill ── */
        .mode-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .mode-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .mode-test {
            background: var(--amber-bg);
            color: var(--amber);
        }

        .mode-test::before {
            background: var(--amber);
        }

        .mode-live {
            background: var(--green-bg);
            color: var(--green);
        }

        .mode-live::before {
            background: var(--green);
        }

        @media(max-width:768px) {
            .settings-page {
                padding: 16px;
            }
        }
    </style>

    <div class="app-content content container-fluid">
        <div class="settings-page">

            <!-- Page header -->
            <div class="page-header">
                <div>
                    <h1>Admin Settings</h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        Admin Settings
                    </div>
                </div>
            </div>

            <!-- Tab shell -->
            <div class="tab-shell">

                <!-- Tab navigation -->
                <div class="tab-nav">
                    <button class="tab-btn {{ $activeTab == 'general' ? 'active' : '' }}"
                        onclick="switchTab('general', this)">
                        <i class="fa-solid fa-sliders"></i> General Settings
                    </button>
                    <button class="tab-btn {{ $activeTab == 'smtp' ? 'active' : '' }}"
                        onclick="switchTab('smtp', this)">
                        <i class="fa-solid fa-envelope"></i> SMTP / Email
                    </button>
                    <button class="tab-btn {{ $activeTab == 'payment' ? 'active' : '' }}"
                        onclick="switchTab('payment', this)">
                        <i class="fa-solid fa-credit-card"></i> Payment Gateway
                    </button>
                    <button class="tab-btn {{ $activeTab == 'gst' ? 'active' : '' }}" onclick="switchTab('gst', this)">
                        <i class="fa-solid fa-file-invoice"></i> GST &amp; Invoice
                    </button>
                    <button class="tab-btn {{ $activeTab == 'couriers' ? 'active' : '' }}"
                        onclick="switchTab('couriers', this)">
                        <i class="fa-solid fa-truck"></i> Courier Management
                    </button>
                    <button class="tab-btn {{ $activeTab == 'sms' ? 'active' : '' }}"
    onclick="switchTab('sms', this)">
    <i class="fa-solid fa-truck"></i> SMS
</button>
<button class="tab-btn {{ $activeTab == 'tracking' ? 'active' : '' }}"
    onclick="switchTab('tracking', this)">
    <i class="fa-brands fa-google"></i> Tracking &amp; Pixels
</button>
                </div>

                <!-- ══════════════════════════════════
                     TAB 1 — GENERAL SETTINGS
                ══════════════════════════════════ -->
                <div class="tab-panel {{ $activeTab == 'general' ? 'active' : '' }}" id="tab-general">
                    @include('admin.admin-settings.general')

                </div><!-- /tab-general -->

                <!-- ══════════════════════════════════
                     TAB 2 — SMTP / EMAIL
                ══════════════════════════════════ -->
                <div class="tab-panel{{ $activeTab == 'smtp' ? 'active' : '' }}" id="tab-smtp">
                    @include('admin.admin-settings.smtp')

                </div><!-- /tab-smtp -->

                <!-- ══════════════════════════════════
                     TAB 3 — PAYMENT GATEWAY
                ══════════════════════════════════ -->
                <div class="tab-panel {{ $activeTab == 'payment' ? 'active' : '' }}" id="tab-payment">
                    @include('admin.admin-settings.payment')
                </div><!-- /tab-payment -->

                <!-- ══════════════════════════════════
                     TAB 4 — GST & INVOICE
                ══════════════════════════════════ -->
                <div class="tab-panel {{ $activeTab == 'gst' ? 'active' : '' }}" id="tab-gst">

                    @include('admin.admin-settings.invoice-gst')
                </div><!-- /tab-gst -->

                <div class="tab-panel {{ $activeTab == 'couriers' ? 'active' : '' }}" id="tab-couriers">

                    @include('admin.admin-settings.couriers')

                </div>
<div class="tab-panel {{ $activeTab == 'sms' ? 'active' : '' }}" id="tab-sms">
    @include('admin.admin-settings.sms')
</div>

<div class="tab-panel {{ $activeTab == 'tracking' ? 'active' : '' }}" id="tab-tracking">
    @include('admin.admin-settings.google-setting')
</div>

            </div><!-- /tab-shell -->

        </div>
    </div>
</div>

@include('admin.footer')

<script>
    // ── Tab switching ──
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + name).classList.add('active');
    }

    // ── Save feedback ──
    function saveSettings(btn) {
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving…';
        setTimeout(() => {
            btn.innerHTML = '<i class="fa fa-check"></i> Saved!';
            btn.style.background = '#007a5e';
            setTimeout(() => {
                btn.innerHTML = orig;
                btn.style.background = '';
                btn.disabled = false;
            }, 2000);
        }, 800);
    }



    // ── Toggle password visibility ──
    function togglePass(id, btn) {
        const input = document.getElementById(id);
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        btn.querySelector('i').className = isPass ? 'fa fa-eye-slash' : 'fa fa-eye';
    }

    // ── Razorpay mode toggle ──
    function toggleMode(checkbox) {
        const pill = document.getElementById('modePill');
        if (checkbox.checked) {
            pill.textContent = 'Live Mode';
            pill.className = 'mode-pill mode-live';
        } else {
            pill.textContent = 'Test Mode';
            pill.className = 'mode-pill mode-test';
        }
    }

    // ── Invoice number preview ──
    function updatePreview() {
        const prefix = document.getElementById('invPrefix').value || 'INV';
        const serial = document.getElementById('invSerial').value || '1001';
        const yearMode = document.getElementById('invYear').value;
        const sep = document.getElementById('invSep').value;

        const now = new Date();
        const y = now.getFullYear();
        const nextY = (y + 1).toString().slice(-2);

        let yearPart = '';
        if (yearMode === 'slash') yearPart = `${y}-${nextY}`;
        if (yearMode === 'year') yearPart = `${y}`;

        const parts = [prefix];
        if (yearPart) parts.push(yearPart);
        parts.push(serial);

        document.getElementById('serialPreview').textContent = parts.join(sep);
    }

    // Init preview on load
    updatePreview();

    // ── Section sidenav smooth scroll ──
    document.querySelectorAll('.settings-sidenav a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            this.closest('.settings-sidenav').querySelectorAll('a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
        });
    });

    @if(!empty($invoice_setting->company_state))
        $('#state_id').trigger('change');
    @endif

    $('#state_id').on('change', function () {

        let stateId = $(this).val();

        $('#city_id').html('<option value="">Loading...</option>');

        if (stateId) {

            $.ajax({
                url: "{{ route('get-cities') }}",
                type: "GET",
                data: {
                    state_id: stateId
                },
                success: function (response) {

                    let html = '<option value="">Select City</option>';

                    $.each(response, function (key, city) {
                        html += `<option value="${city.id}">${city.name}</option>`;
                    });

                    $('#city_id').html(html);
                }
            });

        } else {

            $('#city_id').html('<option value="">Select City</option>');

        }
    });


    // Image preview
    document.getElementById('imageInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    function clearImage() {
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreview').style.display = 'none';
    }


    // logo preview
    document.getElementById('logo').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewlogo').src = e.target.result;
            document.getElementById('logoPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    function clearImage() {
        document.getElementById('logo').value = '';
        document.getElementById('logoPreview').style.display = 'none';
    }

    // favicon preview
    document.getElementById('favicon').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewFavicon').src = e.target.result;
            document.getElementById('faviconPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    function clearImage() {
        document.getElementById('favicon').value = '';
        document.getElementById('faviconPreview').style.display = 'none';
    }

    function toggleBusinessType() {

        let businessType = $('#business_type').val();

        if (businessType === 'registered') {

            $('.gst-field').show();

        } else {

            $('.gst-field').hide();

        }
    }

    $(document).ready(function () {

        toggleBusinessType();

        $('#business_type').on('change', function () {

            toggleBusinessType();

        });

    });

</script>