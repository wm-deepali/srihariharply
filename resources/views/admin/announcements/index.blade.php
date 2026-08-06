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

        .settings-page { background: var(--bg); padding: 24px 28px; min-height: 100vh; font-family: var(--font); color: var(--text-primary); }
        .settings-page * { box-sizing: border-box; }

        .page-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
        .page-header h1 { font-size: 20px; font-weight: 650; margin: 0; }
        .crumb { font-size: 12.5px; color: var(--text-hint); margin-top: 3px; }
        .crumb a { color: var(--accent); text-decoration: none; }
        .crumb a:hover { text-decoration: underline; }
        .crumb span { margin: 0 5px; }

        .btn-primary-dash, .btn-secondary-dash, .btn-danger-dash {
            display: inline-flex; align-items: center; gap: 6px;
            border-radius: var(--radius-sm); padding: 9px 18px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            text-decoration: none !important; font-family: var(--font); border: none;
        }
        .btn-primary-dash { background: var(--accent); color: #fff !important; }
        .btn-primary-dash:hover { background: #252f70; }
        .btn-secondary-dash { background: var(--surface); color: var(--text-primary) !important; border: 1px solid var(--border); font-weight: 500; }
        .btn-secondary-dash:hover { background: var(--bg); }

        .settings-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); overflow: hidden; }

        .info-banner { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 13px; }
        .info-banner.green { background: var(--green-bg); border: 1px solid #b0ddd0; color: var(--green); }
        .info-banner.red { background: var(--red-bg); border: 1px solid #f5c0c0; color: var(--red); }

        .bars-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .bars-table thead th { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--text-hint); padding: 10px 16px; border-bottom: 1px solid var(--border); background: #fafafa; text-align: left; }
        .bars-table tbody tr { border-bottom: 1px solid var(--border); }
        .bars-table tbody tr:last-child { border-bottom: none; }
        .bars-table tbody td { padding: 13px 16px; vertical-align: middle; }

        .bar-swatch { display: inline-flex; align-items: center; gap: 8px; padding: 4px 10px; border-radius: 6px; font-size: 12px; max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .status-pill { display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
        .status-active { background: var(--green-bg); color: var(--green); }
        .status-active::before { background: var(--green); }
        .status-inactive { background: #f1f2f4; color: var(--text-hint); }
        .status-inactive::before { background: var(--text-hint); }

        .action-icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text-secondary); font-size: 12px; cursor: pointer; text-decoration: none; }
        .action-icon-btn:hover { background: var(--bg); color: var(--text-primary); }

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--bg); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; color: var(--text-hint); margin-bottom: 14px; }

        /* ── Modal ── */
        .aq-modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
        .aq-modal-backdrop.show { display: flex; }
        .aq-modal { background: #fff; border-radius: var(--radius-md); width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,.2); }
        .aq-modal-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid var(--border); }
        .aq-modal-header h3 { font-size: 16px; font-weight: 650; margin: 0; }
        .aq-modal-close { background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-hint); }
        .aq-modal-body { padding: 22px 24px; }
        .aq-modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px; border-top: 1px solid var(--border); background: #fafafa; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .col-full { grid-column: 1 / -1; }
        @media(max-width:560px) { .form-grid { grid-template-columns: 1fr; } .col-full { grid-column: 1; } }

        .field-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
        .field-label { font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; }
        .field-label .req { color: var(--red); margin-left: 2px; }
        .field-input { width: 100%; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 12px; font-size: 13.5px; height: 38px; outline: none; font-family: var(--font); }
        .field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }

        .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; }
        .toggle-info-label { font-size: 13px; font-weight: 500; }
        .toggle-switch { position: relative; width: 38px; height: 22px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-track { position: absolute; inset: 0; background: var(--border); border-radius: 22px; cursor: pointer; transition: background .2s; }
        .toggle-track::after { content: ''; position: absolute; left: 3px; top: 3px; width: 16px; height: 16px; background: #fff; border-radius: 50%; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
        .toggle-switch input:checked + .toggle-track { background: var(--accent); }
        .toggle-switch input:checked + .toggle-track::after { transform: translateX(16px); }

        @media(max-width:768px) { .settings-page { padding: 16px; } }
    </style>

    <div class="app-content content container-fluid">
        <div class="settings-page">

            <div class="page-header">
                <div>
                    <h1>Announcement Bars</h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        Announcement Bars
                    </div>
                </div>
                <button class="btn-primary-dash" onclick="openBarModal()">
                    <i class="fa fa-plus"></i> Add Announcement Bar
                </button>
            </div>

            @if(session('success'))
                <div class="info-banner green">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
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

            <div class="settings-card">
                <table class="bars-table">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Link</th>
                            <th>Colors</th>
                            <th>Status</th>
                            <th>Dismissible</th>
                            <th style="width:130px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bars as $bar)
                            <tr>
                                <td style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $bar->message }}
                                </td>
                                <td>
                                    @if($bar->link_text)
                                        <span style="color:var(--accent);font-weight:600;">{{ $bar->link_text }}</span>
                                    @else
                                        <span style="color:var(--text-hint);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="bar-swatch" style="background:{{ $bar->bg_color }};color:{{ $bar->text_color }};">
                                        {{ $bar->bg_color }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.announcements.toggle', $bar) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" style="border:none;background:none;padding:0;cursor:pointer;">
                                            <span class="status-pill {{ $bar->is_active ? 'status-active' : 'status-inactive' }}">
                                                {{ $bar->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $bar->is_dismissible ? 'Yes' : 'No' }}</td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <button type="button" class="action-icon-btn" title="Edit"
                                            onclick='openBarModal(@json($bar))'>
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <form action="{{ route('admin.announcements.destroy', $bar) }}" method="POST"
                                            onsubmit="return confirm('Ye announcement bar delete karna hai?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon-btn" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon"><i class="fa fa-bullhorn"></i></div>
                                        <p style="font-size:15px;font-weight:600;margin:0 0 6px">No announcement bars yet</p>
                                        <p style="font-size:13px;color:var(--text-hint);margin:0">Click "Add Announcement Bar" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- ── Add/Edit Modal ── --}}
<div class="aq-modal-backdrop" id="barModalBackdrop">
    <div class="aq-modal">
        <div class="aq-modal-header">
            <h3 id="barModalTitle">Add Announcement Bar</h3>
            <button type="button" class="aq-modal-close" onclick="closeBarModal()">&times;</button>
        </div>
        <form id="barForm" method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf
            <div id="barMethodField"></div>

            <div class="aq-modal-body">
                <div class="form-grid">
                    <div class="field-group col-full">
                        <label class="field-label">Message <span class="req">*</span></label>
                        <input type="text" name="message" id="barMessage" class="field-input" required
                            placeholder="e.g. Free shipping on orders above ₹999!">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Link Text</label>
                        <input type="text" name="link_text" id="barLinkText" class="field-input" placeholder="e.g. Shop Now">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Link URL</label>
                        <input type="text" name="link_url" id="barLinkUrl" class="field-input" placeholder="e.g. /shop">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Background Color</label>
                        <input type="color" name="bg_color" id="barBgColor" class="field-input" style="padding:4px;" value="#1F5552">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Text Color</label>
                        <input type="color" name="text_color" id="barTextColor" class="field-input" style="padding:4px;" value="#FFFFFF">
                    </div>
                </div>

                <div class="toggle-row">
                    <div class="toggle-info-label">Show Announcement Bar</div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" id="barIsActive" value="1" checked>
                        <span class="toggle-track"></span>
                    </label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-info-label">Allow Customer to Dismiss</div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_dismissible" id="barIsDismissible" value="1" checked>
                        <span class="toggle-track"></span>
                    </label>
                </div>

                <div style="margin-top:18px;">
                    <label class="field-label" style="margin-bottom:8px;display:block;">Live Preview</label>
                    <div id="barPreview" style="background:#1F5552;color:#fff;padding:12px 20px;border-radius:8px;font-size:13px;text-align:center;">
                        <span id="barPreviewText">Your announcement message here</span>
                        <a href="#" id="barPreviewLink" style="color:inherit;font-weight:700;text-decoration:underline;margin-left:8px;display:none;" onclick="return false;"></a>
                    </div>
                </div>
            </div>

            <div class="aq-modal-footer">
                <button type="button" class="btn-secondary-dash" onclick="closeBarModal()">Cancel</button>
                <button type="submit" class="btn-primary-dash"><i class="fa fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

@include('admin.footer')

<script>
    const backdrop      = document.getElementById('barModalBackdrop');
    const form           = document.getElementById('barForm');
    const methodField    = document.getElementById('barMethodField');
    const title           = document.getElementById('barModalTitle');

    const messageInput   = document.getElementById('barMessage');
    const linkTextInput  = document.getElementById('barLinkText');
    const linkUrlInput   = document.getElementById('barLinkUrl');
    const bgInput         = document.getElementById('barBgColor');
    const textColorInput = document.getElementById('barTextColor');
    const activeInput    = document.getElementById('barIsActive');
    const dismissInput   = document.getElementById('barIsDismissible');

    const preview      = document.getElementById('barPreview');
    const previewText  = document.getElementById('barPreviewText');
    const previewLink  = document.getElementById('barPreviewLink');

    function updatePreview() {
        previewText.textContent = messageInput.value || 'Your announcement message here';
        preview.style.background = bgInput.value;
        preview.style.color = textColorInput.value;
        if (linkTextInput.value) {
            previewLink.textContent = linkTextInput.value;
            previewLink.style.display = 'inline';
        } else {
            previewLink.style.display = 'none';
        }
    }

    [messageInput, bgInput, textColorInput, linkTextInput].forEach(el =>
        el.addEventListener('input', updatePreview)
    );

    function openBarModal(bar) {
        form.reset();
        methodField.innerHTML = '';

        if (bar) {
            // Edit mode
            title.textContent = 'Edit Announcement Bar';
            form.action = `{{ url('admin/announcements') }}/${bar.id}`;
            methodField.innerHTML = '@method('PUT')';

            messageInput.value    = bar.message ?? '';
            linkTextInput.value   = bar.link_text ?? '';
            linkUrlInput.value    = bar.link_url ?? '';
            bgInput.value          = bar.bg_color ?? '#1F5552';
            textColorInput.value  = bar.text_color ?? '#FFFFFF';
            activeInput.checked   = !!bar.is_active;
            dismissInput.checked  = !!bar.is_dismissible;
        } else {
            // Add mode
            title.textContent = 'Add Announcement Bar';
            form.action = "{{ route('admin.announcements.store') }}";
            bgInput.value = '#1F5552';
            textColorInput.value = '#FFFFFF';
            activeInput.checked = true;
            dismissInput.checked = true;
        }

        updatePreview();
        backdrop.classList.add('show');
    }

    function closeBarModal() {
        backdrop.classList.remove('show');
    }

    backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closeBarModal();
    });
</script>