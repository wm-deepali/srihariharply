@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    <style>
        .cke_notifications_area,
        .cke_notification,
        .cke_notification_warning {
            display: none !important;
        }

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

        .product-create-page {
            background: var(--bg);
            padding: 24px 28px;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text-primary);
        }

        .product-create-page * {
            box-sizing: border-box;
        }

        /* ── Page header ────────────────────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
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

        /* ── Layout ─────────────────────────────────────────────── */
        .product-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:960px) {
            .product-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Section card ───────────────────────────────────────── */
        .section-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .section-card:last-child {
            margin-bottom: 0;
        }

        .section-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-card-header h5 {
            font-size: 13px;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0;
        }

        .section-card-body {
            padding: 20px;
        }

        /* ── Form fields ────────────────────────────────────────── */
        .field-group {
            margin-bottom: 16px;
        }

        .field-group:last-child {
            margin-bottom: 0;
        }

        .field-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: .03em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .field-label .req {
            color: var(--red);
            margin-left: 2px;
        }

        .field-input,
        .field-select,
        .field-textarea {
            width: 100%;
            height: 38px;
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

        .field-input:focus,
        .field-select:focus,
        .field-textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        .field-textarea {
            height: auto;
            padding: 10px 12px;
            resize: vertical;
            min-height: 80px;
        }

        .field-input[readonly] {
            background: var(--bg);
            color: var(--text-secondary);
            cursor: default;
        }

        .field-hint {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 4px;
        }

        /* ── Slug prefix ────────────────────────────────────────── */
        .slug-wrap {
            position: relative;
        }

        .slug-prefix {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            padding: 0 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-right: none;
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
            font-size: 12px;
            color: var(--text-hint);
            white-space: nowrap;
            pointer-events: none;
        }

        .slug-input {
            padding-left: 68px !important;
        }

        /* ── Pricing row ────────────────────────────────────────── */
        .pricing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        @media(max-width:640px) {
            .pricing-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .final-price-box {
            background: var(--accent-light);
            border: 1px solid #c7cdf5;
            border-radius: var(--radius-sm);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 16px;
        }

        .final-price-box .label {
            font-size: 12px;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .final-price-box .value {
            font-size: 22px;
            font-weight: 700;
            color: var(--accent);
        }

        /* ── Inventory grid ─────────────────────────────────────── */
        .inv-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* ── Checkbox toggles ───────────────────────────────────── */
        .check-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all .15s;
            background: var(--surface);
            margin-bottom: 8px;
        }

        .check-toggle:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .check-toggle input[type="checkbox"] {
            accent-color: var(--accent);
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            cursor: pointer;
            margin: 0;
        }

        .check-toggle input[type="checkbox"]:checked~span {
            font-weight: 600;
            color: var(--accent);
        }

        .check-toggle:has(input:checked) {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .check-toggle span {
            font-size: 13px;
            color: var(--text-primary);
        }

        /* ── Image upload ───────────────────────────────────────── */
        .file-upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 24px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .file-upload-area input[type=file] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .file-upload-area .upload-icon {
            font-size: 24px;
            color: var(--text-hint);
            margin-bottom: 8px;
        }

        .file-upload-area p {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0;
        }

        .file-upload-area small {
            font-size: 11.5px;
            color: var(--text-hint);
        }

        /* Thumb previews */
        #previewContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .thumb-box {
            position: relative;
        }

        .thumb-box img {
            width: 76px;
            height: 76px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1.5px solid var(--border);
            display: block;
        }

        .remove-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--red);
            color: #fff;
            border: 2px solid #fff;
            border-radius: 50%;
            font-size: 11px;
            width: 20px;
            height: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            padding: 0;
        }

        .thumb-default {
            text-align: center;
            margin-top: 4px;
            font-size: 11px;
            color: var(--text-hint);
        }

        .thumb-default input {
            accent-color: var(--accent);
        }

        /* ── Settings toggle rows ───────────────────────────────── */
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--bg);
        }

        .toggle-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .toggle-row:first-child {
            padding-top: 0;
        }

        .toggle-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .toggle-sub {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 2px;
        }

        .field-select-sm {
            height: 32px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 28px 0 10px;
            font-size: 12.5px;
            color: var(--text-primary);
            background: var(--surface);
            outline: none;
            font-family: var(--font);
            min-width: 90px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238c9196'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 9px center;
            transition: border-color .15s, box-shadow .15s;
        }

        .field-select-sm:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        /* ── Attributes (dynamic) ───────────────────────────────── */
        #attribute-container .section-card-body label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        #attribute-container .check-toggle {
            margin-bottom: 6px;
        }

        /* ── Variants table (dynamic) ───────────────────────────── */
        .variants-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .variants-table thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--text-hint);
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
            white-space: nowrap;
            text-align: left;
        }

        .variants-table tbody tr {
            border-bottom: 1px solid var(--border);
        }

        .variants-table tbody tr:last-child {
            border-bottom: none;
        }

        .variants-table tbody td {
            padding: 10px 12px;
            vertical-align: middle;
        }

        .variants-table .field-input {
            height: 34px;
            font-size: 13px;
        }

        .variants-table .field-select {
            height: 34px;
            font-size: 13px;
        }

        .variant-name-cell {
            font-weight: 600;
            font-size: 13px;
            color: var(--text-primary);
            white-space: nowrap;
        }

        /* ── Buttons ────────────────────────────────────────────── */
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

        .btn-primary-dash:hover:not(:disabled) {
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

        .btn-outline-accent {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
            color: var(--accent) !important;
            border: 1.5px solid #c7cdf5;
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: all .15s;
        }

        .btn-outline-accent:hover {
            background: var(--accent);
            color: #fff !important;
            border-color: var(--accent);
        }

        /* ── Action bar ─────────────────────────────────────────── */
        .action-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        /* CKEditor override */
        .cke {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--border) !important;
            overflow: hidden;
        }

        .cke_top {
            background: #fafafa !important;
            border-bottom: 1px solid var(--border) !important;
        }

        @media(max-width:768px) {
            .product-create-page {
                padding: 16px;
            }

            .pricing-grid {
                grid-template-columns: 1fr 1fr;
            }

            .inv-grid {
                grid-template-columns: 1fr;
            }

        }

        .theme-color-picker {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .theme-color-picker input[type="color"] {
            width: 58px;
            height: 58px;
            border: 2px solid var(--border);
            border-radius: 14px;
            cursor: pointer;
            background: #fff;
            padding: 4px;
            overflow: hidden;
            transition: .2s ease;
        }

        .theme-color-picker input[type="color"]:hover {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(48, 61, 137, .12);
        }

        .theme-color-picker input[type="color"]::-webkit-color-swatch {
            border: none;
            border-radius: 10px;
        }

        .theme-color-picker input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        .theme-color-code {
            padding: 10px 14px;
            background: #f8f9fb;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: .04em;
        }
    </style>

    <div class="app-content content container-fluid">
        <div class="product-create-page">

            <!-- Page header -->
            <div class="page-header">
                <div>
                    <h1>Add Product</h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        <a href="{{ route('admin.products.index') }}">Products</a>
                        <span>›</span>
                        Add Product
                    </div>
                </div>
            </div>

            {{-- Validation errors summary --}}
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

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
                class="save-form">
                @csrf

                <div class="product-layout">

                    <!-- ══════════ LEFT COLUMN ══════════ -->
                    <div>

                        <!-- Basic Info -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Basic Information</h5>
                            </div>
                            <div class="section-card-body">

                                <div class="field-group">
                                    <label class="field-label">Category <span class="req">*</span></label>
                                    <select name="category_id" id="category_id" class="field-select" required>
                                        <option value="">— Select Category —</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-group" id="subcategory-wrapper" style="display:none;">
                                    <label class="field-label">Sub Category</label>
                                    <select name="subcategory_id" id="subcategory_id" class="field-select">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Product Name <span class="req">*</span></label>
                                    <input type="text" name="name" id="product_name" class="field-input" required
                                        value="{{ old('name') }}" placeholder="e.g. Hand-Knotted Wool Rug">
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Slug</label>
                                    <div class="slug-wrap">
                                        <span class="slug-prefix">/p/</span>
                                        <input type="text" name="slug" id="slug" class="field-input slug-input"
                                            value="{{ old('slug') }}" placeholder="auto-generated">
                                    </div>
                                    <div class="field-hint">Optional — auto-generated from product name if left blank
                                    </div>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Sub Title</label>
                                    <input type="text" name="sub_title" class="field-input"
                                        value="{{ old('sub_title') }}"></input>
                                </div>

                            </div>
                        </div>

                        <!-- Content -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Content</h5>
                            </div>
                            <div class="section-card-body">

                                <div class="field-group">
                                    <label class="field-label">Product Details</label>
                                    <textarea name="description" id="description"
                                        class="field-textarea">{{ old('description') }}</textarea>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">How To Use</label>
                                    <textarea name="how_to_use" id="how_to_use"
                                        class="field-textarea">{{ old('how_to_use') }}</textarea>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">The Story</label>
                                    <textarea name="the_story" class="field-textarea">{{ old('the_story') }}</textarea>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Detail Page Theme Color</label>

                                    <div class="theme-color-picker">
                                        <input type="color" name="detail_page_color" id="detail_page_color"
                                            value="{{ old('detail_page_color', '#B8832F') }}">

                                        <span class="theme-color-code">
                                            {{ old('detail_page_color', '#B8832F') }}
                                        </span>
                                    </div>

                                    <div class="field-hint">
                                        Used for headings on this product page.
                                    </div>
                                </div>

                                <div class="field-group">
                                    <div
                                        style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                                        <label class="field-label">Product Notes</label>

                                        <button type="button" class="btn-outline-accent" id="add-note-row">
                                            <i class="fa fa-plus"></i> Add More
                                        </button>
                                    </div>

                                    <div id="notes-wrapper">

                                        <div class="note-row" style="display:flex;gap:10px;margin-bottom:10px;">
                                            <input type="text" name="notes[0][title]" class="field-input"
                                                placeholder="Title">

                                            <input type="text" name="notes[0][subtitle]" class="field-input"
                                                placeholder="Sub Title">

                                            <button type="button" class="btn-secondary-dash remove-note-row">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Pricing</h5>
                            </div>
                            <div class="section-card-body">

                                <div class="field-hint" style="margin-bottom:14px;">MRP and Discount are optional. Leave
                                    blank if not applicable — Final Price will simply equal MRP (or 0 if MRP is also
                                    blank).</div>

                                <div class="pricing-grid">
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">MRP</label>
                                        <input type="number" step="0.01" name="mrp" id="mrp" class="field-input"
                                            value="{{ old('mrp') }}" placeholder="0.00">
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">Discount Type</label>
                                        <select name="discount_type" id="discount_type" class="field-select">
                                            <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Amount (₹)</option>
                                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        </select>
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">Discount</label>
                                        <input type="number" step="0.01" name="discount" id="discount"
                                            class="field-input" value="{{ old('discount') }}" placeholder="0">
                                    </div>
                                </div>

                                <div class="final-price-box">
                                    <span class="label">Final Price</span>
                                    <span class="value" id="price-display">₹0.00</span>
                                    <input type="hidden" name="price" id="price" value="0">
                                </div>

                            </div>
                        </div>

                        <!-- Media -->
                        <!-- Media -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Media</h5>
                            </div>
                            <div class="section-card-body">

                                <!-- Card Images -->
                                <p class="field-label" style="margin-bottom:10px;">Card Images</p>
                                <div class="inv-grid" style="gap:14px; margin-bottom:20px;">

                                    <!-- Default Image -->
                                    <div>
                                        <label class="field-label">Default Image <span class="req">*</span></label>
                                        <div class="file-upload-area" id="default-upload-area">
                                            <input type="file" id="default_image" name="card_default" accept="image/*"
                                                onchange="previewSingle(this,'default-preview')">
                                            <div class="upload-icon"><i class="fa fa-image"></i></div>
                                            <p>Click to upload</p>
                                            <small>PNG, JPG, WEBP — 2 MB max</small>
                                        </div>
                                        <div id="default-preview" style="margin-top:10px;"></div>
                                    </div>

                                    <!-- Hover Image -->
                                    <div>
                                        <label class="field-label">Hover Image</label>
                                        <div class="file-upload-area" id="hover-upload-area">
                                            <input type="file" id="hover_image" name="card_hover" accept="image/*"
                                                onchange="previewSingle(this,'hover-preview')">
                                            <div class="upload-icon"><i class="fa fa-image"></i></div>
                                            <p>Click to upload</p>
                                            <small>PNG, JPG, WEBP — 2 MB max</small>
                                        </div>
                                        <div id="hover-preview" style="margin-top:10px;"></div>
                                    </div>
                                </div>

                                <!-- Banner Images -->
                                <label class="field-label">Banner Images</label>
                                <div class="field-hint" style="margin-bottom:10px;">
                                    Multiple allowed — these appear on the product detail page.
                                </div>

                                <div class="file-upload-area">
                                    <input type="file" id="banner_images" name="banner_images[]" multiple
                                        accept="image/*">
                                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                    <p>Click or drag banner images here</p>
                                    <small>PNG, JPG, WEBP — max 10 images, 2 MB each</small>
                                </div>
                                <div id="bannerPreviewContainer"
                                    style="display:flex;flex-wrap:wrap;gap:10px;margin-top:14px;"></div>

                                {{-- ── Story Image ─────────────────────────────────── --}}
                                <div style="margin-top:20px;">
                                    <label class="field-label">Story Image</label>
                                    <div class="field-hint" style="margin-bottom:10px;">
                                        Displayed in "The Story" section on the product page.
                                    </div>
                                    <div class="file-upload-area">
                                        <input type="file" id="story_image" name="story_image" accept="image/*"
                                            onchange="previewSingle(this,'story-preview')">
                                        <div class="upload-icon"><i class="fa fa-image"></i></div>
                                        <p>Click to upload</p>
                                        <small>PNG, JPG, WEBP — 2 MB max</small>
                                    </div>
                                    <div id="story-preview" style="margin-top:10px;"></div>
                                </div>

                            </div>
                        </div>

                        <!-- Attributes (dynamic) -->
                        <!-- <div id="attribute-container"></div> -->

                        <!-- Generate Variants button -->
                        <!-- <div class="mb-3" id="variant-btn-wrapper" style="display:none;">
                            <button type="button" id="generate-variants" class="btn-outline-accent">
                                <i class="fa fa-cogs"></i> Generate Variants
                            </button>
                        </div> -->

                        <!-- Variants (dynamic) -->
                        <!-- <div id="variant-container"></div> -->

                    </div>

                    <!-- ══════════ RIGHT COLUMN ══════════ -->
                    <div>

                        <!-- Status -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Status</h5>
                            </div>
                            <div class="section-card-body" style="padding:16px 20px">
                                <div class="toggle-row" style="padding:0;border:none">
                                    <div>
                                        <div class="toggle-label">Visibility</div>
                                        <div class="toggle-sub">Visible to customers</div>
                                    </div>
                                    <select name="status" class="field-select-sm">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Inventory</h5>
                            </div>
                            <div class="section-card-body">

                                <div class="inv-grid">
                                    <div class="field-group">
                                        <label class="field-label">SKU</label>
                                        <input type="text" name="sku" class="field-input" value="{{ old('sku') }}"
                                            placeholder="SKU-001">
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label">Product Code</label>
                                        <input type="text" name="product_code" class="field-input"
                                            value="{{ old('product_code') }}">
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label">HSN Code</label>
                                        <input type="text" name="hsn_code" class="field-input"
                                            value="{{ old('hsn_code') }}" placeholder="e.g. 6117">
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label">Stock</label>
                                        <input type="number" name="stock" class="field-input"
                                            value="{{ old('stock') }}" placeholder="0">
                                    </div>

                                    <div class="field-group">
                                        <label class="field-label">Weight (in ml)</label>
                                        <input type="number" name="weight" class="field-input"
                                            value="{{ old('weight') }}">
                                    </div>
                                    <!-- <div class="field-group">
                                        <label class="field-label">Min Qty</label>
                                        <input type="number" name="min_qty" class="field-input"
                                            value="{{ old('min_qty') }}" placeholder="1">
                                    </div> -->
                                </div>

                                <!-- <div class="field-group">
                                    <label class="field-label">Delivery Time</label>
                                    <input type="text" name="delivery_time" class="field-input"
                                        value="{{ old('delivery_time') }}" placeholder="e.g. 3–5 business days">
                                </div> -->

                                <!-- <div style="margin-top:4px">
                                    <label class="check-toggle">
                                        <input type="checkbox" name="quality" {{ old('quality') ? 'checked' : '' }}>
                                        <span>Quality Assurance</span>
                                    </label>
                                    <label class="check-toggle">
                                        <input type="checkbox" name="pan_india" {{ old('pan_india') ? 'checked' : '' }}>
                                        <span>PAN India Delivery</span>
                                    </label>
                                </div> -->

                            </div>
                        </div>

                        <!-- Occasions -->
                        <!-- <div class="section-card">
                            <div class="section-card-header">
                                <h5>Occasions</h5>
                            </div>
                            <div class="section-card-body">
                                @foreach($occasions as $o)
                                    <label class="check-toggle">
                                        <input type="checkbox" name="occasions[]" value="{{ $o->id }}" {{ in_array($o->id, old('occasions', [])) ? 'checked' : '' }}>
                                        <span>{{ $o->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div> -->

                        <!-- Collections -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Collections</h5>
                            </div>
                            <div class="section-card-body">
                                @foreach($collections as $collection)
                                    <label class="check-toggle">
                                        <input type="checkbox" name="collections[]" value="{{ $collection->id }}" {{ in_array($collection->id, old('collections', [])) ? 'checked' : '' }}>
                                        <span>{{ $collection->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- SEO -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>SEO</h5>
                            </div>
                            <div class="section-card-body">
                                <div class="field-group">
                                    <label class="field-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="field-input"
                                        value="{{ old('meta_title') }}">
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Meta Description</label>
                                    <textarea name="meta_description"
                                        class="field-textarea">{{ old('meta_description') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Action bar -->
                <div class="action-bar">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary-dash">Cancel</a>
                    <button type="submit" class="btn-primary-dash save-btn">
                        <i class="fa fa-save"></i> Save Product
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
    /* ── CKEditor ───────────────────────────────────────────────── */
    CKEDITOR.config.versionCheck = false;

    CKEDITOR.replace('description');
    CKEDITOR.replace('how_to_use');
</script>


<script>
    /* ── Slug auto-generate ─────────────────────────────────────── */
    $(document).on('keyup', '#product_name', function () {
        let slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        $('#slug').val(slug);
    });

    /* ── Pricing calculator ─────────────────────────────────────── */
    function calcPrice() {
        let m = +$('#mrp').val() || 0;
        let d = +$('#discount').val() || 0;
        let t = $('#discount_type').val();
        let p = t === 'percentage' ? m - (m * d / 100) : m - d;
        if (p < 0) p = 0;
        $('#price').val(p.toFixed(2));
        $('#price-display').text('₹' + p.toFixed(2));
    }
    $('#mrp, #discount, #discount_type').on('keyup change', calcPrice);
    calcPrice(); // ✅ run once on load — keeps hidden #price populated even if MRP/Discount are never touched

    /* ── Submit spinner ─────────────────────────────────────────── */
    $(document).on('submit', '.save-form', function () {
        let btn = $(this).find('.save-btn');
        btn.prop('disabled', true);
        btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });


    /* ── Single image preview (default / hover) ─────────────────── */
    function previewSingle(input, containerId) {
        let container = document.getElementById(containerId);
        container.innerHTML = '';
        if (!input.files || !input.files[0]) return;
        let reader = new FileReader();
        reader.onload = function (e) {
            container.innerHTML = `
            <div class="thumb-box" style="display:inline-block">
                <img src="${e.target.result}" alt="Preview"
                     style="width:76px;height:76px;object-fit:cover;
                            border-radius:var(--radius-sm);border:1.5px solid var(--border);">
                <button type="button" class="remove-btn"
                        onclick="clearSingle('${input.id}','${containerId}')">×</button>
            </div>`;
        };
        reader.readAsDataURL(input.files[0]);
    }

    function clearSingle(inputId, containerId) {
        document.getElementById(inputId).value = '';
        document.getElementById(containerId).innerHTML = '';
    }

    /* ── Banner images preview ──────────────────────────────────── */
    let bannerFiles = [];

    $('#banner_images').on('change', function (e) {
        let files = Array.from(e.target.files);
        if ((bannerFiles.length + files.length) > 10) {
            alert('Maximum 10 banner images allowed');
            return;
        }
        bannerFiles.push(...files);
        renderBannerPreview();
    });

    function renderBannerPreview() {
        let container = $('#bannerPreviewContainer');
        container.html('');
        bannerFiles.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function (e) {
                container.append(`
                <div class="thumb-box">
                    <img src="${e.target.result}" alt="Banner ${index + 1}"
                         style="width:76px;height:76px;object-fit:cover;
                                border-radius:var(--radius-sm);border:1.5px solid var(--border);">
                    <button type="button" class="remove-btn"
                            onclick="removeBanner(${index})">×</button>
                </div>`);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeBanner(index) {
        bannerFiles.splice(index, 1);
        renderBannerPreview();
    }

    /* ── Sync banner file input before submit ───────────────────── */
    $('form').on('submit', function () {
        let dt = new DataTransfer();
        bannerFiles.forEach(f => dt.items.add(f));
        document.getElementById('banner_images').files = dt.files;
    });


    /* ── Category → subcategories & attributes ──────────────────── */
    $('#category_id').on('change', function () {
        let categoryId = $(this).val();
        $('#variant-container').html('');
        $('#variant-btn-wrapper').hide();
        $('#subcategory_id').html('<option value="">Loading...</option>');

        if (!categoryId) {
            $('#subcategory-wrapper').hide();
            $('#attribute-container').html('');
            return;
        }

        // loadAttributes(categoryId);

        window.subCategoryUrl = "{{ url('admin/products/subcategories') }}";
        $.get(window.subCategoryUrl + '/' + categoryId, function (response) {
            if (response.length > 0) {
                let html = '<option value="">Select Sub Category</option>';
                $.each(response, function (i, item) {
                    html += `<option value="${item.id}">${item.name}</option>`;
                });
                $('#subcategory_id').html(html);
                $('#subcategory-wrapper').show();
            } else {
                $('#subcategory-wrapper').hide();
            }
        });
    });

    /* ── Load attributes ────────────────────────────────────────── */
    // function loadAttributes(categoryId) {
    //     $('#attribute-container').html('');
    //     window.attributeUrl = "{{ url('admin/products/category-attributes') }}";

    //     $.get(window.attributeUrl + '/' + categoryId, function (response) {
    //         if (response.length > 0) {
    //             let html = `
    //             <div class="section-card" style="margin-bottom:16px">
    //                 <div class="section-card-header"><h5>Attributes</h5></div>
    //                 <div class="section-card-body">`;

    //             response.forEach(function (item) {
    //                 html += `<div class="field-group">
    //                 <label class="field-label">${item.attribute.name}</label>`;

    //                 if (item.attribute.has_values) {
    //                     item.attribute.values.forEach(function (value) {
    //                         html += `
    //                         <label class="check-toggle">
    //                             <input type="checkbox"
    //                                 class="attribute-value"
    //                                 data-attribute-id="${item.attribute.id}"
    //                                 data-attribute-name="${item.attribute.name}"
    //                                 data-value-name="${value.value}"
    //                                 data-variant="${item.used_for_variant}"
    //                                 name="attribute_values[${item.attribute.id}][]"
    //                                 value="${value.id}">
    //                             <span>${value.value}</span>
    //                         </label>`;
    //                     });
    //                 }
    //                 html += `</div>`;
    //             });

    //             html += `</div></div>`;
    //             $('#attribute-container').html(html);
    //             $('#variant-btn-wrapper').show();
    //         } else {
    //             $('#attribute-container').html('');
    //             $('#variant-btn-wrapper').hide();
    //         }
    //     });
    // }

    /* ── Generate variants ──────────────────────────────────────── */
    // $(document).on('click', '#generate-variants', function () {
    //     let variantAttributes = {};

    //     $('.attribute-value:checked').each(function () {
    //         if ($(this).data('variant') != 1) return;
    //         let attributeId = $(this).data('attribute-id');
    //         if (!variantAttributes[attributeId]) variantAttributes[attributeId] = [];
    //         variantAttributes[attributeId].push({ id: $(this).val(), name: $(this).data('value-name') });
    //     });

    //     let groups = Object.values(variantAttributes);
    //     if (groups.length === 0) {
    //         alert('Please select at least one value from attributes marked as Variant.');
    //         return;
    //     }

    //     renderVariants(cartesian(groups));
    // });

    // function cartesian(arr) {
    //     if (arr.length === 1) return arr[0].map(item => [item]);
    //     return arr.reduce(function (a, b) {
    //         return a.flatMap(function (d) {
    //             return b.map(function (e) { return [].concat(d, e); });
    //         });
    //     });
    // }

    // function renderVariants(combinations) {
    //     let html = `
    //     <div class="section-card" style="margin-bottom:16px">
    //         <div class="section-card-header"><h5>Variants</h5></div>
    //         <div class="section-card-body" style="padding:0;overflow-x:auto">
    //             <table class="variants-table">
    //                 <thead>
    //                     <tr>
    //                         <th>Variant</th>
    //                         <th>SKU</th>
    //                         <th>MRP</th>
    //                         <th>Discount Type</th>
    //                         <th>Discount</th>
    //                         <th>Final Price</th>
    //                         <th>Stock</th>
    //                         <th>Image</th>
    //                     </tr>
    //                 </thead>
    //                 <tbody>`;

    //     combinations.forEach(function (combo, index) {
    //         if (!Array.isArray(combo)) combo = [combo];
    //         let names = combo.map(x => x.name);

    //         html += `<tr>
    //         <td><span class="variant-name-cell">${names.join(' / ')}</span></td>
    //         <td><input type="text" name="variants[${index}][sku]" class="field-input"></td>
    //         <td><input type="number" step="0.01" name="variants[${index}][mrp]" class="field-input"></td>
    //         <td>
    //             <select name="variants[${index}][discount_type]" class="field-select">
    //                 <option value="amount">Amount</option>
    //                 <option value="percentage">%</option>
    //             </select>
    //         </td>
    //         <td><input type="number" step="0.01" name="variants[${index}][discount]" class="field-input"></td>
    //         <td><input type="number" step="0.01" name="variants[${index}][price]" class="field-input" readonly></td>
    //         <td><input type="number" name="variants[${index}][stock]" class="field-input"></td>
    //         <td><input type="file" name="variants[${index}][image]" class="field-input" style="height:auto;padding:4px 8px;font-size:12px"></td>
    //     </tr>`;

    //         combo.forEach(function (item) {
    //             html += `<input type="hidden" name="variants[${index}][values][]" value="${item.id}">`;
    //         });
    //     });

    //     html += `</tbody></table></div></div>`;
    //     $('#variant-container').html(html);
    // }

    /* ── Variant price calculator ───────────────────────────────── */
    $(document).on(
        'keyup change',
        'input[name$="[mrp]"], input[name$="[discount]"], select[name$="[discount_type]"]',
        function () {
            let row = $(this).closest('tr');
            let mrp = parseFloat(row.find('input[name$="[mrp]"]').val()) || 0;
            let discount = parseFloat(row.find('input[name$="[discount]"]').val()) || 0;
            let discountType = row.find('select[name$="[discount_type]"]').val();
            let finalPrice = discountType === 'percentage' ? mrp - (mrp * discount / 100) : mrp - discount;
            if (finalPrice < 0) finalPrice = 0;
            row.find('input[name$="[price]"]').val(finalPrice.toFixed(2));
        }
    );
</script>
<script>
    let noteIndex = 1;

    $(document).on('click', '#add-note-row', function () {

        $('#notes-wrapper').append(`
        <div class="note-row" style="display:flex;gap:10px;margin-bottom:10px;">
            <input type="text"
                   name="notes[${noteIndex}][title]"
                   class="field-input"
                   placeholder="Title">

            <input type="text"
                   name="notes[${noteIndex}][subtitle]"
                   class="field-input"
                   placeholder="Sub Title">

            <button type="button"
                    class="btn-secondary-dash remove-note-row">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    `);

        noteIndex++;
    });

    $(document).on('click', '.remove-note-row', function () {
        $(this).closest('.note-row').remove();
    });
</script>
@include('admin.footer')