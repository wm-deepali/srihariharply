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
.section-card-header { padding: 14px 20px; border-bottom: 1px solid var(--border); background: #fafafa; display:flex; align-items:center; justify-content:space-between; }
.section-card-header h5 { font-size: 13px; font-weight: 650; margin: 0; }
.section-card-body { padding: 20px; }
.field-group { margin-bottom: 16px; }
.field-group:last-child { margin-bottom: 0; }
.field-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; margin-bottom: 6px; }
.field-input { width: 100%; height: 38px; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 12px; font-size: 13.5px; outline: none; font-family: var(--font); }
.field-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }
.field-hint { font-size: 11.5px; color: var(--text-hint); margin-top: 5px; }
.btn-primary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff !important; border: none; border-radius: var(--radius-sm); padding: 9px 20px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none !important; font-family: var(--font); }
.btn-primary-dash:hover:not(:disabled) { background: #252f70; }
.btn-primary-dash:disabled { opacity: .65; cursor: not-allowed; }
.btn-secondary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--surface); color: var(--text-primary) !important; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 9px 20px; font-size: 13px; cursor: pointer; text-decoration: none !important; font-family: var(--font); }
.btn-secondary-dash:hover { background: var(--bg); }
.btn-add-row { display: inline-flex; align-items: center; gap: 6px; background: #eef0fb; color: var(--accent) !important; border: 1px solid #d3d8f2; border-radius: var(--radius-sm); padding: 6px 14px; font-size: 12.5px; font-weight: 600; cursor: pointer; text-decoration: none !important; font-family: var(--font); }
.btn-add-row:hover { background: #e2e5f8; }
.btn-remove-row { background: none; border: none; color: var(--red); font-size: 12px; cursor: pointer; padding: 4px 8px; }
.action-bar { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); padding: 14px 20px; display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; max-width: 720px; }
.gallery-cat-row { display: flex; gap: 12px; align-items: flex-end; margin-bottom: 12px; }
.gallery-cat-row:last-child { margin-bottom: 0; }
.gallery-cat-row .field-group { flex: 1; margin-bottom: 0; }
</style>

<div class="app-content content container-fluid">
    <div class="os-form-page">
        <div class="page-header">
            <div>
                <h1>{{ isset($gallery) ? 'Edit Image Category' : 'Add Image Category' }}</h1>
                <div class="crumb">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a><span>›</span>
                    <a href="{{ route('admin.gallery.index') }}">Image Category</a><span>›</span>
                    {{ isset($gallery) ? 'Edit' : 'Add' }}
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

        <form method="POST" action="{{ isset($gallery) ? route('admin.gallery.update', $gallery->id) : route('admin.gallery.store') }}" class="save-form">
            @csrf
            @isset($gallery) @method('PUT') @endisset

            @isset($gallery)
                {{-- EDIT: single category --}}
                <div class="section-card">
                    <div class="section-card-header"><h5>Image Category Details</h5></div>
                    <div class="section-card-body">
                        <div class="field-group">
                            <label class="field-label">Image Category Name</label>
                            <input type="text" name="title" class="field-input" value="{{ old('title', $gallery->title) }}" placeholder="e.g. Events" required>
                        </div>
                    </div>
                </div>
            @else
                {{-- CREATE: multiple rows, add more as needed --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>Image Category Details</h5>
                        <button type="button" class="btn-add-row" id="add-row-btn"><i class="fa fa-plus"></i> Add More</button>
                    </div>
                    <div class="section-card-body">
                        <div id="rows-wrapper">
                            <div class="gallery-cat-row">
                                <div class="field-group">
                                    <label class="field-label">Image Category Name</label>
                                    <input type="text" name="title[]" class="field-input" placeholder="e.g. Events" required>
                                </div>
                                <button type="button" class="btn-remove-row" style="visibility:hidden;"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="field-hint">"Add More" se ek-ek karke jitni categories chahiye utni row jodo.</div>
                    </div>
                </div>
            @endisset

            <div class="action-bar">
                <a href="{{ route('admin.gallery.index') }}" class="btn-secondary-dash">Cancel</a>
                <button type="submit" class="btn-primary-dash save-btn"><i class="fa fa-save"></i> {{ isset($gallery) ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('submit', '.save-form', function () {
        let btn = $(this).find('.save-btn');
        btn.prop('disabled', true);
        btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });

    @if(!isset($gallery))
    $(document).on('click', '#add-row-btn', function () {
        let $clone = $('#rows-wrapper .gallery-cat-row').first().clone();
        $clone.find('input[type=text]').val('');
        $clone.find('.btn-remove-row').css('visibility', 'visible');
        $('#rows-wrapper').append($clone);
    });

    $(document).on('click', '.btn-remove-row', function () {
        if ($('#rows-wrapper .gallery-cat-row').length > 1) {
            $(this).closest('.gallery-cat-row').remove();
        }
    });
    @endif
</script>