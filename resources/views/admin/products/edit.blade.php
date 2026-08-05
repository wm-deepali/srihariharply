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
            --radius-sm: 8px;
            --radius-md: 12px;
            --shadow-card: 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 1px var(--border);
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .edit-page {
            background: var(--bg);
            padding: 24px 28px;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text-primary);
        }

        .edit-page * {
            box-sizing: border-box;
        }

        /* ── Page header ────────────────────────────────────────── */
        .edit-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .edit-page-header h1 {
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

        /* ── Identity chip ─────────────────────────────────────── */
        .prod-identity {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            box-shadow: var(--shadow-card);
        }

        .prod-identity-thumb {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .prod-identity-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: var(--accent-light);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 18px;
            flex-shrink: 0;
        }

        .prod-identity-name {
            font-size: 14px;
            font-weight: 650;
            color: var(--text-primary);
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prod-identity-id {
            font-size: 12px;
            color: var(--text-hint);
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
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
            padding: 8px 18px;
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
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none !important;
            font-family: var(--font);
            transition: background .15s;
            cursor: pointer;
        }

        .btn-secondary-dash:hover {
            background: var(--bg);
        }

        .btn-accent-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
            color: var(--accent) !important;
            border: 1px solid rgba(48, 61, 137, .25);
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: background .15s;
        }

        .btn-accent-outline:hover {
            background: #e3e5f7;
        }

        /* ── Layout ─────────────────────────────────────────────── */
        .edit-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:960px) {
            .edit-layout {
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

        .field-hint {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 4px;
        }

        /* ── Slug prefix ────────────────────────────────────────── */
        .slug-wrap {
            display: flex;
        }

        .slug-prefix {
            display: inline-flex;
            align-items: center;
            padding: 0 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-right: none;
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
            font-size: 12px;
            color: var(--text-hint);
            white-space: nowrap;
        }

        .slug-wrap .field-input {
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        /* ── Price grid ─────────────────────────────────────────── */
        .price-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        @media(max-width:600px) {
            .price-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .final-price-box {
            background: var(--accent-light);
            border: 1px solid #c7cdf5;
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
        }

        .final-price-box .fp-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .final-price-box .fp-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--accent);
        }

        /* ── Inventory grid ─────────────────────────────────────── */
        .inv-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* ── Checkbox pill ──────────────────────────────────────── */
        .check-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface);
            cursor: pointer;
            transition: border-color .15s, background .15s;
            font-size: 13px;
            color: var(--text-primary);
            margin-bottom: 8px;
            user-select: none;
        }

        .check-pill:last-child {
            margin-bottom: 0;
        }

        .check-pill:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .check-pill input[type="checkbox"] {
            accent-color: var(--accent);
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .check-pill input[type="checkbox"]:checked~span {
            font-weight: 600;
            color: var(--accent);
        }

        .check-pill:has(input:checked) {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .check-pill-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 12px;
        }

        @media(max-width:600px) {
            .check-pill-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Media thumbnails ───────────────────────────────────── */
        .media-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 4px;
        }

        .thumb-box {
            position: relative;
        }

        .thumb-box img {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--border);
            display: block;
        }

        .thumb-remove {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--red);
            color: #fff;
            border: 2px solid #fff;
            font-size: 11px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* ── Upload area ────────────────────────────────────────── */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius-sm);
            padding: 20px;
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
            font-size: 20px;
            color: var(--text-hint);
            margin-bottom: 4px;
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

        /* ── Toggle rows (right sidebar) ────────────────────────── */
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
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
            transition: border-color .15s, box-shadow .15s;
            min-width: 100px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238c9196'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 9px center;
        }

        .field-select-sm:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        /* ── Status pills ───────────────────────────────────────── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pill-active {
            background: var(--green-bg);
            color: var(--green);
        }

        .pill-active::before {
            background: var(--green);
        }

        .pill-inactive {
            background: var(--red-bg);
            color: var(--red);
        }

        .pill-inactive::before {
            background: var(--red);
        }

        /* ── Attributes (dynamic) ───────────────────────────────── */
        #attribute-container .section-card,
        #variant-container .section-card {
            margin-bottom: 16px;
        }

        #variant-container table {
            font-size: 12.5px;
        }

        #variant-container table th {
            background: #fafafa;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-secondary);
            font-weight: 650;
            padding: 10px;
        }

        #variant-container table td {
            padding: 8px 10px;
            vertical-align: middle;
        }

        #variant-container .form-control,
        #attribute-container .form-control {
            height: 34px;
            border-radius: var(--radius-sm);
            font-size: 12.5px;
            border: 1px solid var(--border);
            background: var(--surface);
            font-family: var(--font);
            padding: 0 10px;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        #variant-container .form-control:focus,
        #attribute-container .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        #attribute-container .attr-check-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 6px;
        }

        #attribute-container .attr-check-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface);
            font-size: 12.5px;
            cursor: pointer;
            transition: border-color .12s, background .12s;
        }

        #attribute-container .attr-check-item:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        #attribute-container .attr-check-item input {
            accent-color: var(--accent);
        }

        /* ── CKEditor ───────────────────────────────────────────── */
        .cke {
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--border) !important;
            overflow: hidden;
        }

        .cke_top {
            background: #fafafa !important;
            border-bottom: 1px solid var(--border) !important;
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

        @media(max-width:768px) {
            .edit-page {
                padding: 16px;
            }

            .price-grid {
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
        <div class="edit-page">

            <!-- Page header -->
            <div class="edit-page-header">
                <div>
                    <h1>Edit Product</h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        <a href="{{ route('admin.products.index') }}">Products</a>
                        <span>›</span>
                        Edit Product
                    </div>
                </div>

                <!-- Identity chip -->
                <div class="prod-identity">
                    @php $defaultImg = $product->images->firstWhere('is_default', true) ?? $product->images->first(); @endphp
                    @if($defaultImg)
                        <img src="{{ asset('storage/' . $defaultImg->image) }}" class="prod-identity-thumb"
                            alt="{{ $product->name }}">
                    @else
                        <div class="prod-identity-icon"><i class="fa fa-box"></i></div>
                    @endif
                    <div>
                        <div class="prod-identity-name">{{ $product->name }}</div>
                        <div class="prod-identity-id">
                            ID #{{ $product->id }}
                            &middot;
                            @if($product->status)
                                <span class="pill pill-active">Active</span>
                            @else
                                <span class="pill pill-inactive">Inactive</span>
                            @endif
                        </div>
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

            <form method="POST" action="{{ route('admin.products.update', $product->id) }}"
                enctype="multipart/form-data" class="save-form" id="productForm">
                @csrf
                @method('PUT')

                <div class="edit-layout">

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
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-group" id="subcategory-wrapper"
                                    style="{{ $product->category_id ? '' : 'display:none' }}">
                                    <label class="field-label">Sub Category</label>
                                    <select name="subcategory_id" id="subcategory_id" class="field-select">
                                        <option value="">Select Sub Category</option>
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Product Name <span class="req">*</span></label>
                                    <input type="text" name="name" id="product_name" class="field-input"
                                        value="{{ old('name', $product->name) }}" required>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Slug</label>
                                    <div class="slug-wrap">
                                        <span class="slug-prefix">product/</span>
                                        <input type="text" name="slug" id="slug" class="field-input"
                                            value="{{ old('slug', $product->slug) }}">
                                    </div>
                                    <div class="field-hint">Optional — auto-generated from product name if left blank
                                    </div>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Sub Title</label>
                                    <input type="text" name="sub_title" class="field-input"
                                        value="{{ old('sub_title', $product->sub_title) }}"></input>
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
                                    <textarea name="description" id="description" class="field-textarea"
                                        style="min-height:140px">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">How To Use</label>
                                    <textarea name="how_to_use" id="how_to_use" class="field-textarea"
                                        style="min-height:100px">{{ old('how_to_use', $product->how_to_use) }}</textarea>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">The Story</label>
                                    <textarea name="the_story" class="field-textarea"
                                        style="min-height:100px">{{ old('the_story', $product->the_story) }}</textarea>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Detail Page Theme Color</label>

                                    <div class="theme-color-picker">
                                        <input type="color" id="detail_page_color" name="detail_page_color"
                                            value="{{ old('detail_page_color', $product->detail_page_color ?? '#B8832F') }}">

                                        <span class="theme-color-code">
                                            {{ old('detail_page_color', $product->detail_page_color ?? '#B8832F') }}
                                        </span>
                                    </div>

                                    <div class="field-hint">
                                        Used for headings on the product page.
                                    </div>
                                </div>

                                @php
                                    $notes = old('notes');

                                    if (!$notes) {
                                        $notes = is_array($product->product_notes)
                                            ? $product->product_notes
                                            : json_decode($product->product_notes ?? '[]', true);
                                    }

                                    $notes = $notes ?: [['title' => '', 'subtitle' => '']];
                                @endphp

                                <div class="field-group">
                                    <div
                                        style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                                        <label class="field-label">Product Notes</label>

                                        <button type="button" class="btn-accent-outline" id="add-note-row">
                                            <i class="fa fa-plus"></i> Add More
                                        </button>
                                    </div>

                                    <div id="notes-wrapper">

                                        @foreach($notes as $index => $note)
                                            <div class="note-row" style="display:flex;gap:10px;margin-bottom:10px;">
                                                <input type="text" name="notes[{{ $index }}][title]" class="field-input"
                                                    placeholder="Title" value="{{ $note['title'] ?? '' }}">

                                                <input type="text" name="notes[{{ $index }}][subtitle]" class="field-input"
                                                    placeholder="Sub Title" value="{{ $note['subtitle'] ?? '' }}">

                                                <button type="button" class="btn-secondary-dash remove-note-row">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach

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

                                <div class="price-grid">
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">MRP</label>
                                        <input type="number" step="0.01" name="mrp" id="mrp" class="field-input"
                                            value="{{ old('mrp', $product->mrp) }}">
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">Discount Type</label>
                                        <select name="discount_type" id="discount_type" class="field-select">
                                            <option value="amount" {{ old('discount_type', $product->discount_type) == 'amount' ? 'selected' : '' }}>Amount (₹)
                                            </option>
                                            <option value="percentage" {{ old('discount_type', $product->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage
                                                (%)</option>
                                        </select>
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">Discount</label>
                                        <input type="number" step="0.01" name="discount" id="discount"
                                            class="field-input" value="{{ old('discount', $product->discount) }}">
                                    </div>
                                </div>

                                <div class="final-price-box">
                                    <span class="fp-label">Final Price</span>
                                    <span class="fp-value">₹<span
                                            id="price-display">{{ old('price', $product->price) }}</span></span>
                                    <input type="hidden" name="price" id="price"
                                        value="{{ old('price', $product->price) }}">
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

                                <!-- ── Card Images ─────────────────────────────── -->
                                <p class="field-label" style="margin-bottom:10px;">Card Images</p>
                                <div class="inv-grid" style="gap:14px; margin-bottom:20px;">

                                    <!-- Default Image -->
                                    <div>
                                        <label class="field-label">Default Image</label>
                                        @php $defaultImg = $product->images->firstWhere('image_type', 'default'); @endphp
                                        @if($defaultImg)
                                            <div class="thumb-box" id="existing_default_wrap" style="margin-bottom:10px;">
                                                <img src="{{ asset('storage/' . $defaultImg->image) }}"
                                                    style="width:80px;height:80px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);">
                                                <button type="button" class="thumb-remove"
                                                    onclick="removeExistingTyped({{ $defaultImg->id }}, 'existing_default_wrap')">×</button>
                                                <div class="thumb-default"
                                                    style="font-size:11px;color:var(--text-hint);margin-top:4px;">Current
                                                </div>
                                            </div>
                                        @endif
                                        <div class="upload-area">
                                            <input type="file" id="default_image" name="card_default" accept="image/*"
                                                onchange="previewSingle(this,'default-preview')">
                                            <div class="upload-icon"><i class="fa fa-image"></i></div>
                                            <div class="upload-label" style="font-size:13px;">
                                                {{ $defaultImg ? 'Replace' : 'Upload' }}
                                            </div>
                                            <div class="upload-sub">PNG, JPG, WEBP — 2 MB max</div>
                                        </div>
                                        <div id="default-preview" style="margin-top:10px;"></div>
                                    </div>

                                    <!-- Hover Image -->
                                    <div>
                                        <label class="field-label">Hover Image</label>
                                        @php $hoverImg = $product->images->firstWhere('image_type', 'hover'); @endphp
                                        @if($hoverImg)
                                            <div class="thumb-box" id="existing_hover_wrap" style="margin-bottom:10px;">
                                                <img src="{{ asset('storage/' . $hoverImg->image) }}"
                                                    style="width:80px;height:80px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);">
                                                <button type="button" class="thumb-remove"
                                                    onclick="removeExistingTyped({{ $hoverImg->id }}, 'existing_hover_wrap')">×</button>
                                                <div class="thumb-default"
                                                    style="font-size:11px;color:var(--text-hint);margin-top:4px;">Current
                                                </div>
                                            </div>
                                        @endif
                                        <div class="upload-area">
                                            <input type="file" id="hover_image" name="card_hover" accept="image/*"
                                                onchange="previewSingle(this,'hover-preview')">
                                            <div class="upload-icon"><i class="fa fa-image"></i></div>
                                            <div class="upload-label" style="font-size:13px;">
                                                {{ $hoverImg ? 'Replace' : 'Upload' }}
                                            </div>
                                            <div class="upload-sub">PNG, JPG, WEBP — 2 MB max</div>
                                        </div>
                                        <div id="hover-preview" style="margin-top:10px;"></div>
                                    </div>
                                </div>

                                <!-- ── Banner Images ───────────────────────────── -->
                                <label class="field-label">Banner Images</label>
                                <div class="field-hint" style="margin-bottom:10px;">
                                    These appear on the product detail page. Remove individual banners below, then
                                    upload new ones.
                                </div>

                                @php $bannerImgs = $product->images->where('image_type', 'banner'); @endphp
                                @if($bannerImgs->count())
                                    <div class="media-grid" style="margin-bottom:14px;" id="existingBanners">
                                        @foreach($bannerImgs as $img)
                                            <div class="thumb-box" id="banner_{{ $img->id }}">
                                                <img src="{{ asset('storage/' . $img->image) }}"
                                                    style="width:80px;height:80px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);">
                                                <button type="button" class="thumb-remove"
                                                    onclick="removeExistingTyped({{ $img->id }}, 'banner_{{ $img->id }}')">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="upload-area">
                                    <input type="file" id="banner_images" name="banner_images[]" multiple
                                        accept="image/*">
                                    <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                    <div class="upload-label" style="font-size:13px;">Click or drag banner images here
                                    </div>
                                    <div class="upload-sub">PNG, JPG, WEBP — max 10 images, 2 MB each</div>
                                </div>
                                <div id="bannerPreviewContainer"
                                    style="display:flex;flex-wrap:wrap;gap:10px;margin-top:14px;"></div>


                                {{-- ── Story Image ─────────────────────────────────── --}}
                                <div style="margin-top:20px;">
                                    <label class="field-label">Story Image</label>
                                    <div class="field-hint" style="margin-bottom:10px;">
                                        Displayed in "The Story" section on the product page.
                                    </div>
                                    @php $storyImg = $product->images->firstWhere('image_type', 'story'); @endphp
                                    @if($storyImg)
                                        <div class="thumb-box" id="existing_story_wrap"
                                            style="margin-bottom:10px;display:inline-block;">
                                            <img src="{{ asset('storage/' . $storyImg->image) }}"
                                                style="width:80px;height:80px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--border);">
                                            <button type="button" class="thumb-remove"
                                                onclick="removeExistingTyped({{ $storyImg->id }}, 'existing_story_wrap')">×</button>
                                            <div class="thumb-default">Current</div>
                                        </div>
                                    @endif
                                    <div class="upload-area">
                                        <input type="file" id="story_image" name="story_image" accept="image/*"
                                            onchange="previewSingle(this,'story-preview')">
                                        <div class="upload-icon"><i class="fa fa-image"></i></div>
                                        <div class="upload-label" style="font-size:13px;">
                                            {{ isset($storyImg) && $storyImg ? 'Replace' : 'Upload' }}
                                        </div>
                                        <div class="upload-sub">PNG, JPG, WEBP — 2 MB max</div>
                                    </div>
                                    <div id="story-preview" style="margin-top:10px;"></div>
                                </div>

                            </div>
                        </div>

                    </div><!-- /left column -->

                    <!-- ══════════ RIGHT COLUMN ══════════ -->
                    <div>

                        <!-- Status -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Status</h5>
                            </div>
                            <div class="section-card-body" style="padding:14px 20px">
                                <div class="toggle-row" style="padding:0;border:none">
                                    <div>
                                        <div class="toggle-label">Visibility</div>
                                        <div class="toggle-sub">Shown on storefront</div>
                                    </div>
                                    <select name="status" class="field-select-sm">
                                        <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>
                                            Inactive</option>
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

                                <div class="inv-grid" style="margin-bottom:14px">
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">SKU</label>
                                        <input type="text" name="sku" class="field-input"
                                            value="{{ old('sku', $product->sku) }}">
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">Product Code</label>
                                        <input type="text" name="product_code" class="field-input"
                                            value="{{ old('product_code', $product->product_code) }}">
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">HSN Code</label>
                                        <input type="text" name="hsn_code" class="field-input"
                                            value="{{ old('hsn_code', $product->hsn_code) }}" placeholder="e.g. 6117">
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">Stock</label>
                                        <input type="number" name="stock" class="field-input"
                                            value="{{ old('stock', $product->stock) }}" placeholder="0">
                                    </div>
                                    <div class="field-group" style="margin:0">
                                        <label class="field-label">Weight (in ml)</label>
                                        <input type="number" name="weight" class="field-input"
                                            value="{{ old('weight', $product->weight) }}">
                                    </div>
                                    <!-- <div class="field-group" style="margin:0">
                                        <label class="field-label">Min Qty</label>
                                        <input type="number" name="min_qty" class="field-input"
                                               value="{{ old('min_qty', $product->min_qty) }}">
                                    </div> -->
                                </div>

                                <!-- <div class="field-group">
                                    <label class="field-label">Delivery Time</label>
                                    <input type="text" name="delivery_time" class="field-input"
                                           value="{{ old('delivery_time', $product->delivery_time) }}"
                                           placeholder="e.g. 3–5 business days">
                                </div> -->

                                <!-- <div>
    <label class="check-pill">
        <input type="checkbox" name="quality" {{ old('quality', $product->quality) ? 'checked' : '' }}>
        <span>Quality Assurance</span>
    </label>
    <label class="check-pill">
        <input type="checkbox" name="pan_india" {{ old('pan_india', $product->pan_india) ? 'checked' : '' }}>
        <span>PAN India Delivery</span>
    </label>
</div> -->

                            </div>
                        </div>

                        <!-- Occasions -->
                        <!-- <div class="section-card">
                            <div class="section-card-header"><h5>Occasions</h5></div>
                            <div class="section-card-body">
                                @foreach($occasions as $o)
                                    <label class="check-pill">
                                        <input type="checkbox" name="occasions[]" value="{{ $o->id }}"
                                               {{ in_array($o->id, old('occasions', $selectedOccasions)) ? 'checked' : '' }}>
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
                                    <label class="check-pill">
                                        <input type="checkbox" name="collections[]" value="{{ $collection->id }}" {{ in_array($collection->id, old('collections', $product->collections->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                        value="{{ old('meta_title', $product->meta_title) }}">
                                </div>

                                <div class="field-group">
                                    <label class="field-label">Meta Description</label>
                                    <textarea name="meta_description"
                                        class="field-textarea">{{ old('meta_description', $product->meta_description) }}</textarea>
                                </div>

                            </div>
                        </div>

                    </div><!-- /right column -->

                </div><!-- /edit-layout -->

                <!-- ── Attributes & Variants (full width below grid) ── -->
                <!-- <div id="attribute-container"></div> -->
                <!-- 
                <div id="variant-btn-wrapper" style="display:none; margin: 16px 0;">
                    <button type="button" id="generate-variants" class="btn-accent-outline">
                        <i class="fa fa-cogs"></i> Generate Variants
                    </button>
                </div> -->

                <!-- <div id="variant-container"></div> -->

                <!-- Action bar -->
                <div class="action-bar">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary-dash">Cancel</a>
                    <button type="submit" id="saveBtn" class="btn-primary-dash save-btn">
                        <i class="fa fa-save"></i> Update Product
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
    CKEDITOR.disableAutoInline = true;

    // Disable version warning
    CKEDITOR.config.versionCheck = false;

    CKEDITOR.replace('description');
    CKEDITOR.replace('how_to_use');
</script>

<script>
    let selectedAttributeValues = @json($selectedAttributeValues);
    let existingVariants = @json($existingVariants);

    let noteIndex = $('#notes-wrapper .note-row').length;

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

    $(document).ready(function () {
        let categoryId = $('#category_id').val();
        if (categoryId) {
            loadAttributes(categoryId);
            if (existingVariants.length > 0) renderExistingVariants();
        }
        calcPrice(); // ✅ keep hidden #price + display in sync on load, in case product had no MRP/discount saved
    });

    // Slug auto-gen
    $(document).on('keyup', '#product_name', function () {
        $('#slug').val($(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''));
    });

    // Price calc — also updates the visible display span
    function calcPrice() {
        let m = +$('#mrp').val() || 0;
        let d = +$('#discount').val() || 0;
        let t = $('#discount_type').val();
        let p = t == 'percentage' ? m - (m * d / 100) : m - d;
        if (p < 0) p = 0;
        $('#price').val(p.toFixed(2));
        $('#price-display').text(p.toFixed(2));
    }
    $('#mrp,#discount,#discount_type').on('keyup change', calcPrice);

    // Submit spinner
    $(document).on('submit', '.save-form', function () {
        let btn = $(this).find('.save-btn');
        btn.prop('disabled', true);
        btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...');
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
                <img src="${e.target.result}"
                     style="width:80px;height:80px;object-fit:cover;
                            border-radius:var(--radius-sm);border:1px solid var(--border);">
                <button type="button" class="thumb-remove"
                        onclick="clearSingle('${input.id}','${containerId}')">×</button>
            </div>`;
        };
        reader.readAsDataURL(input.files[0]);
    }

    function clearSingle(inputId, containerId) {
        document.getElementById(inputId).value = '';
        document.getElementById(containerId).innerHTML = '';
    }

    /* ── Remove existing typed image (default / hover / banner) ─── */
    function removeExistingTyped(id, wrapperId) {
        if (!confirm('Remove this image?')) return;
        document.getElementById(wrapperId).remove();
        $('<input>').attr({ type: 'hidden', name: 'delete_images[]', value: id }).appendTo('form');
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
                    <img src="${e.target.result}"
                         style="width:80px;height:80px;object-fit:cover;
                                border-radius:var(--radius-sm);border:1px solid var(--border);">
                    <button type="button" class="thumb-remove"
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

    /* ── Sync banner files before submit ────────────────────────── */
    $('form').on('submit', function () {
        let dt = new DataTransfer();
        bannerFiles.forEach(f => dt.items.add(f));
        document.getElementById('banner_images').files = dt.files;
    });

    // ── Category → sub + attributes ──────────────────────────────
    $('#category_id').on('change', function () {
        let categoryId = $(this).val();
        $('#variant-container').html('');
        $('#variant-btn-wrapper').hide();
        $('#subcategory_id').html('<option value="">Loading...</option>');
        if (!categoryId) { $('#subcategory-wrapper').hide(); return; }
        // loadAttributes(categoryId);
        window.subCategoryUrl = "{{ url('admin/products/subcategories') }}";
        $.get(window.subCategoryUrl + '/' + categoryId, function (response) {
            if (response.length > 0) {
                let html = '<option value="">Select Sub Category</option>';
                $.each(response, function (i, item) { html += `<option value="${item.id}">${item.name}</option>`; });
                $('#subcategory_id').html(html);
                $('#subcategory-wrapper').show();
            } else {
                $('#subcategory-wrapper').hide();
            }
        });
    });

    // function loadAttributes(categoryId) {
    //     $('#attribute-container').html('');
    //     window.attributeUrl = "{{ url('admin/products/category-attributes') }}";
    //     $.get(window.attributeUrl + '/' + categoryId, function (response) {
    //         if (response.length > 0) {
    //             let html = `
    //                 <div class="section-card">
    //                     <div class="section-card-header"><h5>Attributes</h5></div>
    //                     <div class="section-card-body">
    //             `;
    //             response.forEach(function (item) {
    //                 html += `<div class="field-group">
    //                     <label class="field-label">${item.attribute.name}</label>`;
    //                 if (item.attribute.has_values) {
    //                     html += `<div class="attr-check-wrap">`;
    //                     item.attribute.values.forEach(function (value) {
    //                         let checked = selectedAttributeValues.includes(value.id) ? 'checked' : '';
    //                         html += `
    //                             <label class="attr-check-item">
    //                                 <input type="checkbox" class="attribute-value"
    //                                     data-attribute-id="${item.attribute.id}"
    //                                     data-attribute-name="${item.attribute.name}"
    //                                     data-value-name="${value.value}"
    //                                     data-variant="${item.used_for_variant}"
    //                                     name="attribute_values[${item.attribute.id}][]"
    //                                     value="${value.id}" ${checked}>
    //                                 ${value.value}
    //                             </label>`;
    //                     });
    //                     html += `</div>`;
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

    // $(document).on('click', '#generate-variants', function () {
    //     let variantAttributes = {};
    //     $('.attribute-value:checked').each(function () {
    //         if ($(this).data('variant') != 1) return;
    //         let attributeId = $(this).data('attribute-id');
    //         if (!variantAttributes[attributeId]) variantAttributes[attributeId] = [];
    //         variantAttributes[attributeId].push({ id: $(this).val(), name: $(this).data('value-name') });
    //     });
    //     let groups = Object.values(variantAttributes);
    //     if (groups.length === 0) { alert('Please select at least one value from attributes marked as Variant.'); return; }
    //     renderVariants(cartesian(groups));
    // });

    // function cartesian(arr) {
    //     if (arr.length === 1) return arr[0].map(item => [item]);
    //     return arr.reduce((a, b) => a.flatMap(d => b.map(e => [].concat(d, e))));
    // }

    // function variantRowHTML(index, variantName, data = {}) {
    //     return `
    //     <tr>
    //         <td>${variantName}${data.id ? `<input type="hidden" name="variants[${index}][id]" value="${data.id}">` : ''}</td>
    //         <td><input type="text"   name="variants[${index}][sku]"           class="form-control" value="${data.sku ?? ''}"></td>
    //         <td><input type="number" name="variants[${index}][mrp]"           class="form-control" step="0.01" value="${data.mrp ?? ''}"></td>
    //         <td>
    //             <select name="variants[${index}][discount_type]" class="form-control">
    //                 <option value="amount"     ${(data.discount_type ?? '') == 'amount'     ? 'selected' : ''}>Amount</option>
    //                 <option value="percentage" ${(data.discount_type ?? '') == 'percentage' ? 'selected' : ''}>%</option>
    //             </select>
    //         </td>
    //         <td><input type="number" name="variants[${index}][discount]"      class="form-control" step="0.01" value="${data.discount ?? ''}"></td>
    //         <td><input type="number" name="variants[${index}][price]"         class="form-control" step="0.01" value="${data.price ?? ''}" readonly></td>
    //         <td><input type="number" name="variants[${index}][stock]"         class="form-control" value="${data.stock ?? ''}"></td>
    //         <td>
    //             ${data.image ? `<img src="/storage/${data.image}" style="width:50px;height:50px;object-fit:cover;border-radius:6px;border:1px solid var(--border);margin-bottom:6px;display:block">` : ''}
    //             <input type="file" name="variants[${index}][image]" class="form-control" style="height:auto;padding:4px">
    //             ${data.image ? `<input type="hidden" name="variants[${index}][old_image]" value="${data.image}">` : ''}
    //         </td>
    //     </tr>`;
    // }

    // function variantTableWrap(tbody) {
    //     return `<div class="section-card" style="margin-bottom:16px">
    //         <div class="section-card-header"><h5>Variants</h5></div>
    //         <div class="section-card-body" style="padding:0;overflow-x:auto">
    //             <table class="table table-bordered" style="margin:0">
    //                 <thead><tr>
    //                     <th>Variant</th><th>SKU</th><th>MRP</th><th>Discount Type</th><th>Discount</th><th>Final Price</th><th>Stock</th><th>Image</th>
    //                 </tr></thead>
    //                 <tbody>${tbody}</tbody>
    //             </table>
    //         </div>
    //     </div>`;
    // }

    // function renderVariants(combinations) {
    //     let tbody = '';
    //     combinations.forEach(function (combo, index) {
    //         if (!Array.isArray(combo)) combo = [combo];
    //         let names = combo.map(x => x.name).join(' / ');
    //         let hiddenInputs = combo.map(x => `<input type="hidden" name="variants[${index}][values][]" value="${x.id}">`).join('');
    //         tbody += variantRowHTML(index, names + hiddenInputs);
    //     });
    //     $('#variant-container').html(variantTableWrap(tbody));
    // }

    // function renderExistingVariants() {
    //     let tbody = '';
    //     existingVariants.forEach(function (variant, index) {
    //         tbody += variantRowHTML(index, variant.variant_name, variant);
    //     });
    //     $('#variant-container').html(variantTableWrap(tbody));
    // }

    // Variant price calc

    $(document).on('keyup change',
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

@include('admin.footer')