@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    <style>
        /* ── Design Tokens ──────────────────────────────────────── */
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
            --radius-sm: 8px;
            --radius-md: 12px;
            --shadow-card: 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 1px var(--border);
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .create-page {
            background: var(--bg);
            padding: 24px 28px;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text-primary);
        }

        .create-page * {
            box-sizing: border-box;
        }

        /* ── Page header ────────────────────────────────────────── */
        .create-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .create-page-header h1 {
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
            transition: background .15s, box-shadow .15s;
            box-shadow: 0 1px 3px rgba(48, 61, 137, .25);
        }

        .btn-primary-dash:hover {
            background: #252f70;
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
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: background .15s;
        }

        .btn-secondary-dash:hover {
            background: var(--bg);
        }

        /* ── Two-column layout ──────────────────────────────────── */
        .create-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:900px) {
            .create-layout {
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
        }

        .section-card-header h5 {
            font-size: 13px;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0;
            letter-spacing: .01em;
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

        .field-hint {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 4px;
        }

        /* ── Slug field ─────────────────────────────────────────── */
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
            padding-left: 76px !important;
        }

        /* ── File upload ────────────────────────────────────────── */
        .file-upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 28px 20px;
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
            font-size: 26px;
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

        /* ── Toggle / select rows ───────────────────────────────── */
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
            transition: border-color .15s, box-shadow .15s;
            min-width: 90px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238c9196'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 9px center;
        }

        .field-select-sm:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        /* ── Action bar (sticky bottom) ─────────────────────────── */
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
            .create-page {
                padding: 16px;
            }
        }
    </style>

    <div class="app-content content container-fluid">
        <div class="create-page">

            <!-- Page header -->
            <div class="create-page-header">
                <div>
                    <h1>Add Coupon</h1>

                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        <a href="{{ route('admin.coupons.index') }}">Coupons</a>
                        <span>›</span>
                        Add Coupon
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.coupons.store') }}">
                @csrf
                <div class="create-layout">

                    <!-- ── LEFT column ──────────────────────────────── -->
                    <div>

                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Coupon Information</h5>
                            </div>

                            <div class="section-card-body">

                                <div class="field-group">
                                    <label class="field-label">
                                        Coupon Code
                                        <span class="req">*</span>
                                    </label>

                                    <input type="text" name="code" class="field-input" required placeholder="WELCOME10">
                                </div>

                                <div class="field-group">
                                    <label class="field-label">
                                        Discount Type
                                    </label>

                                    <select name="discount_type" class="field-select">

                                        <option value="percentage">
                                            Percentage
                                        </option>

                                        <option value="fixed">
                                            Fixed Amount
                                        </option>

                                    </select>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">
                                        Discount Value
                                    </label>

                                    <input type="number" step="0.01" name="discount_value" class="field-input" required>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">
                                        Minimum Order Amount
                                    </label>

                                    <input type="number" step="0.01" name="minimum_order_amount" class="field-input">
                                </div>

                                <div class="field-group">
                                    <label class="field-label">
                                        Maximum Discount
                                    </label>

                                    <input type="number" step="0.01" name="maximum_discount" class="field-input">
                                </div>

<div class="field-group">
    <label class="field-label">
        Minimum Order Quantity
    </label>

    <input type="number" min="1" name="minimum_order_quantity" class="field-input" placeholder="e.g. 2">

</div>

                                <div class="field-group">
                                    <label class="field-label">
                                        Coupon For
                                    </label>

                                    <select name="customer_type" class="field-select">
                                        <option value="all">For All Customers</option>
                                        <option value="new">For New Customers Only</option>
                                    </select>
                                </div>

                            </div>
                        </div>



                    </div>

                    <!-- ── RIGHT column ─────────────────────────────── -->
                    <div>

                        <div class="section-card">

                            <div class="section-card-header">
                                <h5>Validity</h5>
                            </div>

                            <div class="section-card-body">

                                <div class="field-group">
                                    <label class="field-label">
                                        Start Date
                                    </label>

                                    <input type="date" name="start_date" class="field-input" required>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">
                                        End Date
                                    </label>

                                    <input type="date" name="end_date" class="field-input" required>
                                </div>

                                <div class="field-group">
                                    <label class="field-label">
                                        Usage Limit (Usage Per Customer)
                                    </label>

                                    <input type="number" name="usage_limit" class="field-input">
                                </div>

                            </div>

                        </div>

                        <!-- Settings -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>Settings</h5>
                            </div>
                            <div class="section-card-body" style="padding:16px 20px">

                                <div class="toggle-row">

                                    <div>
                                        <div class="toggle-label">
                                            Status
                                        </div>

                                        <div class="toggle-sub">
                                            Enable / Disable Coupon
                                        </div>
                                    </div>

                                    <select name="status" class="field-select-sm">

                                        <option value="1">
                                            Active
                                        </option>

                                        <option value="0">
                                            Inactive
                                        </option>

                                    </select>

                                </div>

                                <div class="toggle-row">

                                    <div>
                                        <div class="toggle-label">
                                            Coupon Visibility
                                        </div>

                                        <div class="toggle-sub">
                                            Show or hide coupon from website coupon popup
                                        </div>
                                    </div>

                                    <select name="visibility" class="field-select-sm">
                                        <option value="public">Show On Website</option>
                                        <option value="private">Make It Private</option>
                                    </select>

                                </div>


                            </div>
                        </div>

                    </div>
                </div>

                <!-- Action bar -->
                <div class="action-bar">
                    <a href="{{ route('admin.coupons.index') }}" class="btn-secondary-dash">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary-dash">
                        <i class="fa fa-save"></i> Save Coupon
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@include('admin.footer')