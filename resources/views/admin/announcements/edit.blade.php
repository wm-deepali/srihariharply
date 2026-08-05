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

        /* ── Card shell ── */
        .settings-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .settings-content {
            padding: 28px 32px;
        }

        @media(max-width:860px) {
            .settings-content {
                padding: 20px;
            }
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
            margin: 0 0 22px;
        }

        /* ── Form grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .col-full {
            grid-column: 1 / -1;
        }

        @media(max-width:640px) {
            .form-grid {
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

        .field-input {
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
            height: 38px;
        }

        .field-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
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

        /* ── Info banner ── */
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

        .info-banner.green {
            background: var(--green-bg);
            border: 1px solid #b0ddd0;
            color: var(--green);
        }

        .info-banner.red {
            background: var(--red-bg);
            border: 1px solid #f5c0c0;
            color: var(--red);
        }

        /* ── Action bar ── */
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
                    <h1>Announcement Bar</h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        Announcement Bar
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="info-banner green">
                    <i class="fa-solid fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="info-banner red">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <ul style="margin:0;padding-left:16px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Card -->
            <div class="settings-card">
                <div class="settings-content">

                    <div class="settings-section-title">
                        <i class="fa-solid fa-bullhorn"></i> Announcement Bar
                    </div>

                    <form method="POST" action="{{ route('admin.announcements.update') }}">
                        @csrf

                        <div class="form-grid">

                            <div class="field-group col-full">
                                <label class="field-label">Message <span class="req">*</span></label>
                                <input type="text" name="message" class="field-input"
                                    placeholder="e.g. Free shipping on orders above ₹999!"
                                    value="{{ old('message', $bar->message ?? '') }}">
                            </div>

                            <div class="field-group">
                                <label class="field-label">Link Text</label>
                                <input type="text" name="link_text" class="field-input"
                                    placeholder="e.g. Shop Now"
                                    value="{{ old('link_text', $bar->link_text ?? '') }}">
                                <span class="field-hint">Optional — leave blank to show plain text only.</span>
                            </div>

                            <div class="field-group">
                                <label class="field-label">Link URL</label>
                                <input type="text" name="link_url" class="field-input"
                                    placeholder="e.g. /shop"
                                    value="{{ old('link_url', $bar->link_url ?? '') }}">
                            </div>

                            <div class="field-group">
                                <label class="field-label">Background Color</label>
                                <input type="color" name="bg_color" id="bgColorInput" class="field-input" style="padding:4px;"
                                    value="{{ old('bg_color', $bar->bg_color ?? '#1F5552') }}">
                                <span class="field-hint">Default: dark green — same as hero banner.</span>
                            </div>

                            <div class="field-group">
                                <label class="field-label">Text Color</label>
                                <input type="color" name="text_color" id="textColorInput" class="field-input" style="padding:4px;"
                                    value="{{ old('text_color', $bar->text_color ?? '#FFFFFF') }}">
                            </div>

                        </div>

                        <div style="margin-top:24px;">
                            <div class="toggle-row">
                                <div>
                                    <div class="toggle-info-label">Show Announcement Bar</div>
                                    <div class="toggle-info-sub">Turn off to hide the bar from the site entirely.</div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bar->is_active ?? 1) ? 'checked' : '' }}>
                                    <span class="toggle-track"></span>
                                </label>
                            </div>

                            <div class="toggle-row">
                                <div>
                                    <div class="toggle-info-label">Allow Customer to Dismiss</div>
                                    <div class="toggle-info-sub">Shows a close (×) button; stays hidden for that visitor's session after closing.</div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_dismissible" value="1" {{ old('is_dismissible', $bar->is_dismissible ?? 1) ? 'checked' : '' }}>
                                    <span class="toggle-track"></span>
                                </label>
                            </div>
                        </div>

                        {{-- Live Preview --}}
                        <div style="margin-top:24px;">
                            <label class="field-label" style="margin-bottom:8px;display:block;">Live Preview</label>
                            <div id="announcement-preview"
                                style="background: {{ old('bg_color', $bar->bg_color ?? '#1F5552') }}; color: {{ old('text_color', $bar->text_color ?? '#FFFFFF') }}; padding:12px 20px; border-radius:8px; font-size:13px; text-align:center;">
                                <span id="preview-text">{{ old('message', $bar->message ?? 'Your announcement message here') }}</span>
                                <a href="#" id="preview-link" style="color:inherit;font-weight:700;text-decoration:underline;margin-left:8px; {{ ($bar->link_text ?? '') ? '' : 'display:none;' }}" onclick="return false;">
                                    {{ old('link_text', $bar->link_text ?? '') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="action-bar">
                    <button class="btn-secondary-dash" type="button" onclick="window.location.reload()">Discard Changes</button>
                    <button class="btn-primary-dash" type="submit" form="" onclick="document.querySelector('form').submit()">
                        <i class="fa fa-save"></i> Save Announcement Bar
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@include('admin.footer')

<script>
    // Live preview updates as admin types
    (function () {
        const msgInput = document.querySelector('[name="message"]');
        const bgInput = document.getElementById('bgColorInput');
        const textColorInput = document.getElementById('textColorInput');
        const linkTextInput = document.querySelector('[name="link_text"]');

        const preview = document.getElementById('announcement-preview');
        const previewText = document.getElementById('preview-text');
        const previewLink = document.getElementById('preview-link');

        if (msgInput) {
            msgInput.addEventListener('input', () => {
                previewText.textContent = msgInput.value || 'Your announcement message here';
            });
        }
        if (bgInput) {
            bgInput.addEventListener('input', () => {
                preview.style.background = bgInput.value;
            });
        }
        if (textColorInput) {
            textColorInput.addEventListener('input', () => {
                preview.style.color = textColorInput.value;
            });
        }
        if (linkTextInput && previewLink) {
            linkTextInput.addEventListener('input', () => {
                if (linkTextInput.value) {
                    previewLink.textContent = linkTextInput.value;
                    previewLink.style.display = 'inline';
                } else {
                    previewLink.style.display = 'none';
                }
            });
        }
    })();
</script>