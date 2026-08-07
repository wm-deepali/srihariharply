{{-- resources/views/admin/logo/_form.blade.php --}}
<style>
:root {
    --bg:            #f1f2f4;
    --surface:       #ffffff;
    --border:        #e3e5e8;
    --text-primary:  #202223;
    --text-secondary:#6d7175;
    --text-hint:     #8c9196;
    --accent:        #303d89;
    --accent-light:  #f0f1fc;
    --red:           #b22222;
    --radius-sm:     8px;
    --radius-md:     12px;
    --shadow-card:   0 1px 3px rgba(0,0,0,.08), 0 0 0 1px var(--border);
    --font:          'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.logo-form-page { background: var(--bg); padding: 24px 28px; min-height: 100vh; font-family: var(--font); color: var(--text-primary); }
.logo-form-page * { box-sizing: border-box; }

.page-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
.page-header h1 { font-size: 20px; font-weight: 650; color: var(--text-primary); margin: 0; }
.crumb { font-size: 12.5px; color: var(--text-hint); margin-top: 3px; }
.crumb a { color: var(--accent); text-decoration: none; }
.crumb a:hover { text-decoration: underline; }
.crumb span { margin: 0 5px; }

.section-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); overflow: hidden; max-width: 560px; }
.section-card-header { padding: 14px 20px; border-bottom: 1px solid var(--border); background: #fafafa; }
.section-card-header h5 { font-size: 13px; font-weight: 650; color: var(--text-primary); margin: 0; }
.section-card-body { padding: 20px; }

.field-group { margin-bottom: 16px; }
.field-group:last-child { margin-bottom: 0; }
.field-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; margin-bottom: 6px; }
.field-label .req { color: var(--red); margin-left: 2px; }
.field-input { width: 100%; height: 38px; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 12px; font-size: 13.5px; color: var(--text-primary); background: var(--surface); outline: none; transition: border-color .15s, box-shadow .15s; font-family: var(--font); }
.field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }
.field-hint { font-size: 11.5px; color: var(--text-hint); margin-top: 4px; }

.file-upload-area { border: 2px dashed var(--border); border-radius: var(--radius-md); padding: 24px 20px; text-align: center; cursor: pointer; transition: border-color .15s, background .15s; position: relative; }
.file-upload-area:hover { border-color: var(--accent); background: var(--accent-light); }
.file-upload-area input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.file-upload-area .upload-icon { font-size: 24px; color: var(--text-hint); margin-bottom: 8px; }
.file-upload-area p { font-size: 13px; color: var(--text-secondary); margin: 0; }
.file-upload-area small { font-size: 11.5px; color: var(--text-hint); }

.thumb-box { position: relative; display: inline-block; margin-top: 12px; }
.thumb-box img { width: 140px; height: 60px; border-radius: var(--radius-sm); object-fit: contain; border: 1.5px solid var(--border); background: #fafafa; display: block; }

.btn-primary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff !important; border: none; border-radius: var(--radius-sm); padding: 9px 20px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none !important; font-family: var(--font); transition: background .15s; box-shadow: 0 1px 3px rgba(48,61,137,.25); }
.btn-primary-dash:hover:not(:disabled) { background: #252f70; }
.btn-primary-dash:disabled { opacity: .65; cursor: not-allowed; }
.btn-secondary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--surface); color: var(--text-primary) !important; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 9px 20px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none !important; font-family: var(--font); transition: background .15s; }
.btn-secondary-dash:hover { background: var(--bg); }

.action-bar { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); padding: 14px 20px; display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 20px; max-width: 560px; }
</style>

<div class="app-content content container-fluid">
    <div class="logo-form-page">

        <div class="page-header">
            <div>
                <h1>{{ isset($logo) ? 'Edit Logo' : 'Add Logo' }}</h1>
                <div class="crumb">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <span>›</span>
                    <a href="{{ route('admin.logo.index') }}">Logo Management</a>
                    <span>›</span>
                    {{ isset($logo) ? 'Edit' : 'Add' }}
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="section-card" style="border-color:#f3b9b9;">
                <div class="section-card-body" style="padding:14px 20px;">
                    <strong style="color:var(--red);font-size:13px;">Please fix the following:</strong>
                    <ul style="margin:8px 0 0 18px; padding:0; font-size:13px; color:var(--red);">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST"
              action="{{ isset($logo) ? route('admin.logo.update', $logo->id) : route('admin.logo.store') }}"
              enctype="multipart/form-data" class="save-form">
            @csrf
            @isset($logo) @method('PUT') @endisset

            <div class="section-card">
                <div class="section-card-header"><h5>Logo Details</h5></div>
                <div class="section-card-body">

                    <div class="field-group">
                        <label class="field-label">Logo Title</label>
                        <input type="text" name="title" class="field-input"
                               value="{{ old('title', $logo->title ?? '') }}" placeholder="e.g. Main Site Logo">
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            Logo Image @if(!isset($logo)) <span class="req">*</span> @endif
                        </label>
                        <div class="file-upload-area">
                            <input type="file" name="image" accept="image/*" onchange="previewLogo(this)">
                            <div class="upload-icon"><i class="fa fa-image"></i></div>
                            <p>Click to upload</p>
                            <small>Recommended size 320×70 — PNG, JPG, WEBP, 2 MB max</small>
                        </div>
                        <div id="logo-preview">
                            @isset($logo)
                                @if($logo->image)
                                    <div class="thumb-box">
                                        <img src="{{ $logo->image_url }}" alt="{{ $logo->title }}">
                                    </div>
                                @endif
                            @endisset
                        </div>
                        <div class="field-hint">Leave blank on edit to keep the current image.</div>
                    </div>

                </div>
            </div>

            <div class="action-bar">
                <a href="{{ route('admin.logo.index') }}" class="btn-secondary-dash">Cancel</a>
                <button type="submit" class="btn-primary-dash save-btn">
                    <i class="fa fa-save"></i> {{ isset($logo) ? 'Update Logo' : 'Save Logo' }}
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function previewLogo(input) {
        let container = document.getElementById('logo-preview');
        container.innerHTML = '';
        if (!input.files || !input.files[0]) return;
        let reader = new FileReader();
        reader.onload = function (e) {
            container.innerHTML = `
            <div class="thumb-box">
                <img src="${e.target.result}" alt="Preview">
            </div>`;
        };
        reader.readAsDataURL(input.files[0]);
    }

    $(document).on('submit', '.save-form', function () {
        let btn = $(this).find('.save-btn');
        btn.prop('disabled', true);
        btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });
</script>c