<style>
:root {
    --bg:#f1f2f4; --surface:#ffffff; --border:#e3e5e8; --text-primary:#202223; --text-secondary:#6d7175;
    --text-hint:#8c9196; --accent:#303d89; --red:#b22222;
    --radius-sm:8px; --radius-md:12px; --shadow-card:0 1px 3px rgba(0,0,0,.08), 0 0 0 1px var(--border);
    --font:'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}
.os-form-page { background: var(--bg); padding: 24px 28px; min-height: 100vh; font-family: var(--font); color: var(--text-primary); }
.os-form-page * { box-sizing: border-box; }
.page-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
.page-header h1 { font-size: 20px; font-weight: 650; margin: 0; }
.crumb { font-size: 12.5px; color: var(--text-hint); margin-top: 3px; }
.crumb a { color: var(--accent); text-decoration: none; }
.crumb span { margin: 0 5px; }
.section-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); overflow: hidden; max-width: 720px; margin-bottom: 16px; }
.section-card-header { padding: 14px 20px; border-bottom: 1px solid var(--border); background: #fafafa; }
.section-card-header h5 { font-size: 13px; font-weight: 650; margin: 0; }
.section-card-body { padding: 20px; }
.field-group { margin-bottom: 16px; }
.field-group:last-child { margin-bottom: 0; }
.field-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; margin-bottom: 6px; }
.field-input, .field-textarea { width: 100%; height: 38px; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 12px; font-size: 13.5px; outline: none; font-family: var(--font); }
.field-input:focus, .field-textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }
.field-textarea { height: auto; padding: 10px 12px; resize: vertical; min-height: 80px; }
.field-hint { font-size: 11.5px; color: var(--text-hint); margin-top: 5px; }
.current-thumb { width: 90px; height: 84px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border); margin-bottom: 10px; display: block; }
.btn-primary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff !important; border: none; border-radius: var(--radius-sm); padding: 9px 20px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none !important; font-family: var(--font); }
.btn-primary-dash:hover:not(:disabled) { background: #252f70; }
.btn-primary-dash:disabled { opacity: .65; cursor: not-allowed; }
.btn-secondary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--surface); color: var(--text-primary) !important; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 9px 20px; font-size: 13px; cursor: pointer; text-decoration: none !important; font-family: var(--font); }
.btn-secondary-dash:hover { background: var(--bg); }
.action-bar { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); padding: 14px 20px; display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; max-width: 720px; }
</style>

<div class="app-content content container-fluid">
    <div class="os-form-page">
        <div class="page-header">
            <div>
                <h1>{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h1>
                <div class="crumb">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a><span>›</span>
                    <a href="{{ route('admin.product-category.index') }}">Category</a><span>›</span>
                    {{ isset($category) ? 'Edit' : 'Add' }}
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="section-card" style="border-color:#f3b9b9;">
                <div class="section-card-body" style="padding:14px 20px;">
                    <strong style="color:var(--red);font-size:13px;">Please fix the following:</strong>
                    <ul style="margin:8px 0 0 18px; padding:0; font-size:13px; color:var(--red);">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ isset($category) ? route('admin.product-category.update', $category->id) : route('admin.product-category.store') }}" enctype="multipart/form-data" class="save-form">
            @csrf
            @isset($category) @method('PUT') @endisset

            <div class="section-card">
                <div class="section-card-header"><h5>Category Details</h5></div>
                <div class="section-card-body">
                    <div class="field-group">
                        <label class="field-label">Title</label>
                        <input type="text" name="title" class="field-input" value="{{ old('title', $category->title ?? '') }}" placeholder="e.g. Handmade Rugs" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Image</label>
                        @isset($category)
                            @if($category->image)
                                <img src="{{ $category->image_url }}" class="current-thumb">
                            @endif
                        @endisset
                        <input type="file" name="image" class="field-input" style="height:auto;padding:8px 12px;">
                        <div class="field-hint">Recommended size 430×400px. Leave blank to keep the current image.</div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Content</label>
                        <textarea name="content" id="content" class="field-textarea">{{ old('content', $category->content ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <a href="{{ route('admin.product-category.index') }}" class="btn-secondary-dash">Cancel</a>
                <button type="submit" class="btn-primary-dash save-btn"><i class="fa fa-save"></i> {{ isset($category) ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.config.versionCheck = false;
    CKEDITOR.replace('content');
    $(document).on('submit', '.save-form', function () {
        let btn = $(this).find('.save-btn');
        btn.prop('disabled', true);
        btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });
</script>