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
.field-row { display: flex; gap: 16px; }
.field-row .field-group { flex: 1; }
.field-group { margin-bottom: 16px; }
.field-group:last-child { margin-bottom: 0; }
.field-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; margin-bottom: 6px; }
.field-input, .field-select { width: 100%; height: 38px; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 12px; font-size: 13.5px; outline: none; font-family: var(--font); background: var(--surface); }
.field-input:focus, .field-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }
.field-hint { font-size: 11.5px; color: var(--text-hint); margin-top: 5px; }
.current-thumb { width: 108px; height: 69px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border); margin-bottom: 10px; display: block; }
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
                <h1>{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h1>
                <div class="crumb">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a><span>›</span>
                    <a href="{{ route('admin.category-details.index') }}">Product</a><span>›</span>
                    {{ isset($product) ? 'Edit' : 'Add' }}
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

        <form method="POST" action="{{ isset($product) ? route('admin.category-details.update', $product->id) : route('admin.category-details.store') }}" enctype="multipart/form-data" class="save-form">
            @csrf
            @isset($product) @method('PUT') @endisset

            <div class="section-card">
                <div class="section-card-header"><h5>Product Details</h5></div>
                <div class="section-card-body">
                    <div class="field-row">
                        <div class="field-group">
                            <label class="field-label">Product Category</label>
                            <select name="product_category_id" class="field-select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id ?? '')==$cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Brand Name</label>
                            <select name="brand_id" class="field-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ old('brand_id', $product->brand_id ?? '')==$b->id ? 'selected' : '' }}>{{ $b->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Product Name</label>
                        <input type="text" name="title" class="field-input" value="{{ old('title', $product->title ?? '') }}" placeholder="e.g. Chikankaari Kurta" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Gallery Image</label>
                        @isset($product)
                            @if($product->image)
                                <img src="{{ $product->image_url }}" class="current-thumb">
                            @endif
                        @endisset
                        <input type="file" name="image" class="field-input" style="height:auto;padding:8px 12px;">
                        <div class="field-hint">Recommended size 270×172px. Leave blank to keep the current image.</div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Product Code</label>
                        <input type="text" name="content" class="field-input" value="{{ old('content', $product->content ?? '') }}" placeholder="Product Code">
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <a href="{{ route('admin.category-details.index') }}" class="btn-secondary-dash">Cancel</a>
                <button type="submit" class="btn-primary-dash save-btn"><i class="fa fa-save"></i> {{ isset($product) ? 'Update' : 'Save' }}</button>
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
</script>