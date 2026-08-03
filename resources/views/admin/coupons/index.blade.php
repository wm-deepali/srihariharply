@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    <style>
        /* ── Design Tokens (same as dashboard) ─────────────────── */
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
            --amber: #916a00;
            --amber-bg: #fff5cc;
            --blue: #0069d9;
            --blue-bg: #e8f2ff;
            --red: #b22222;
            --red-bg: #fce8e8;
            --radius-sm: 8px;
            --radius-md: 12px;
            --shadow-card: 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 1px var(--border);
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ── Page shell ─────────────────────────────────────────── */
        .cat-page {
            background: var(--bg);
            padding: 24px 28px;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text-primary);
            box-sizing: border-box;
        }

        .cat-page * {
            box-sizing: border-box;
        }

        /* ── Page header ────────────────────────────────────────── */
        .cat-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .cat-page-header h1 {
            font-size: 20px;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0;
        }

        .cat-breadcrumb {
            font-size: 12.5px;
            color: var(--text-hint);
            margin-top: 3px;
        }

        .cat-breadcrumb a {
            color: var(--accent);
            text-decoration: none;
        }

        .cat-breadcrumb a:hover {
            text-decoration: underline;
        }

        .cat-breadcrumb span {
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
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none !important;
            transition: background .15s, box-shadow .15s;
            box-shadow: 0 1px 3px rgba(48, 61, 137, .25);
        }

        .btn-primary-dash:hover {
            background: #252f70;
            box-shadow: 0 3px 8px rgba(48, 61, 137, .3);
        }

        .btn-secondary-dash {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--surface);
            color: var(--text-primary) !important;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none !important;
            transition: background .15s, box-shadow .15s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }

        .btn-secondary-dash:hover {
            background: var(--bg);
            box-shadow: 0 2px 6px rgba(0, 0, 0, .07);
        }

        /* ── Surface card ───────────────────────────────────────── */
        .cat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        /* ── Filter bar ─────────────────────────────────────────── */
        .filter-bar {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }

        .filter-bar .form-row-inner {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-group label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .filter-control {
            height: 36px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 11px;
            font-size: 13px;
            color: var(--text-primary);
            background: var(--surface);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            font-family: var(--font);
            min-width: 140px;
        }

        .filter-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        .filter-control-wide {
            min-width: 220px;
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* ── Table ──────────────────────────────────────────────── */
        .cat-table-wrap {
            overflow-x: auto;
        }

        .cat-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            font-family: var(--font);
        }

        .cat-table thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--text-hint);
            padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
            text-align: left;
            white-space: nowrap;
        }

        .cat-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .1s;
        }

        .cat-table tbody tr:last-child {
            border-bottom: none;
        }

        .cat-table tbody tr:hover {
            background: #fafbfc;
        }

        .cat-table tbody td {
            padding: 12px 16px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        /* Sorting link */
        .sort-link {
            color: var(--text-hint);
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color .12s;
        }

        .sort-link:hover {
            color: var(--text-primary);
            text-decoration: none;
        }

        .sort-link .fa-sort {
            opacity: .4;
        }

        .sort-link .fa-sort-up,
        .sort-link .fa-sort-down {
            color: var(--accent);
            opacity: 1;
        }

        /* Category image */
        .cat-img {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--border);
        }

        .cat-img-placeholder {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-hint);
            font-size: 16px;
            border: 1px solid var(--border);
        }

        /* Category name cell */
        .cat-name-cell strong {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 13px;
        }

        .cat-name-cell small {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 1px;
            display: block;
        }

        /* Pills */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .pill::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            display: inline-block;
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

        .pill-yes {
            background: var(--accent-light);
            color: var(--accent);
        }

        .pill-yes::before {
            background: var(--accent);
        }

        .pill-no {
            background: var(--bg);
            color: var(--text-hint);
        }

        .pill-no::before {
            background: var(--text-hint);
        }

        /* Action buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-secondary);
            font-size: 12px;
            cursor: pointer;
            transition: all .12s;
            text-decoration: none;
        }

        .action-btn:hover {
            background: var(--bg);
            color: var(--text-primary);
        }

        .action-btn-danger:hover {
            background: var(--red-bg);
            border-color: #f5c6c6;
            color: var(--red);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 64px 20px;
        }

        .empty-state .empty-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--bg);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--text-hint);
            margin-bottom: 14px;
        }

        .empty-state p {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 6px 0 16px;
        }

        /* Pagination wrapper */
        .cat-pagination {
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: center;
            background: var(--surface);
        }

        /* ID chip */
        .id-chip {
            display: inline-block;
            background: var(--bg);
            color: var(--text-secondary);
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            font-family: 'SF Mono', 'Fira Code', monospace;
        }

        @media (max-width: 768px) {
            .cat-page {
                padding: 16px;
            }

            .filter-bar .form-row-inner {
                flex-direction: column;
            }

            .filter-control {
                min-width: 100%;
            }
        }
    </style>

    <div class="app-content content container-fluid">
        <div class="cat-page">

            <!-- Page header -->
            <div class="cat-page-header">
                <div>
                    <h1>Coupon Management</h1>

                    <div class="cat-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        Coupon Management
                    </div>
                </div>

                <div style="display:flex;gap:8px;flex-wrap:wrap">

                    <a href="{{ route('admin.coupons.create') }}" class="btn-primary-dash">
                        <i class="fa fa-plus"></i>
                        Add Coupon
                    </a>

                </div>
            </div>

            <!-- Main card -->
            <div class="cat-card">

                <!-- Filter bar -->
                <div class="filter-bar">
                    <form method="GET">

                        <div class="form-row-inner">

                            <div class="filter-group">
                                <label>Status</label>

                                <select name="status" class="filter-control">

                                    <option value="">All</option>

                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>
                            </div>

                            <div class="filter-group" style="flex:1">

                                <label>Search Coupon</label>

                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="filter-control filter-control-wide" placeholder="Enter coupon code">

                            </div>

                            <div class="filter-actions">

                                <button type="submit" class="btn-primary-dash">

                                    <i class="fa fa-search"></i>
                                    Search

                                </button>

                                <a href="{{ route('admin.coupons.index') }}" class="btn-secondary-dash">

                                    Reset

                                </a>

                            </div>

                        </div>

                    </form>
                </div>

                <!-- Table -->
                <div class="cat-table-wrap">



                    <table class="cat-table">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Coupon Code</th>

                                <th>Type</th>

                                <th>Discount</th>

                                <th>Min Order</th>
                                
                                <th>Min Qty</th>

                                <th>Usage</th>

                                <th>Validity</th>

                                <th>Status</th>

                                <th>Actions</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($coupons as $coupon)

                                <tr id="row{{ $coupon->id }}">

                                    <td>
                                        <span class="id-chip">
                                            {{ $coupon->id }}
                                        </span>
                                    </td>

                                    <td>
                                        <strong>{{ $coupon->code }}</strong>
                                    </td>

                                    <td>
                                        {{ ucfirst($coupon->discount_type) }}
                                    </td>

                                    <td>

                                        @if($coupon->discount_type == 'percentage')

                                            {{ $coupon->discount_value }} %

                                        @else

                                            ₹{{ number_format($coupon->discount_value, 2) }}

                                        @endif

                                    </td>

                                    <td>
                                        ₹{{ number_format($coupon->minimum_order_amount ?? 0, 2) }}
                                    </td>

<td>
    {{ $coupon->minimum_order_quantity ?? '—' }}
</td>

                                    <td>
                                        {{ $coupon->used_count }}
                                        /
                                        {{ $coupon->usage_limit ?? '∞' }}
                                    </td>

                                    <td>

                                        {{ date('d M Y', strtotime($coupon->start_date)) }}

                                        <br>

                                        <small>

                                            to

                                            {{ date('d M Y', strtotime($coupon->end_date)) }}

                                        </small>

                                    </td>

                                    <td>

                                        @if($coupon->status)

                                            <span class="pill pill-active">
                                                Active
                                            </span>

                                        @else

                                            <span class="pill pill-inactive">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <div style="display:flex;gap:6px">

                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="action-btn">

                                                <i class="fa fa-pencil"></i>

                                            </a>

                                            {{-- Share Coupon Button --}}
                                            <button class="action-btn"
                                                onclick="openShareModal({{ $coupon->id }}, '{{ $coupon->code }}', '{{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : '₹' . number_format($coupon->discount_value, 2) }}', '{{ date('d M Y', strtotime($coupon->end_date)) }}')"
                                                title="Share Coupon">
                                                <i class="fa fa-share-alt"></i>
                                            </button>

                                            <button class="action-btn action-btn-danger"
                                                onclick="deleteCoupon({{ $coupon->id }})">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9">

                                        <div class="empty-state">

                                            <div class="empty-icon">
                                                <i class="fa fa-ticket"></i>
                                            </div>

                                            <strong>
                                                No coupons found
                                            </strong>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <div class="cat-pagination">
                    {{ $coupons->links('pagination::bootstrap-4') }}
                </div>

            </div><!-- /cat-card -->

        </div><!-- /cat-page -->
    </div>
</div>

<!-- Share Coupon Modal -->
<div id="share-coupon-modal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;">
    <div
        style="background:#fff;border-radius:12px;padding:28px 28px 24px;width:100%;max-width:420px;box-shadow:0 8px 32px rgba(0,0,0,.18);position:relative;">

        <button onclick="closeShareModal()"
            style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:18px;color:#6d7175;cursor:pointer;">
            <i class="fa fa-times"></i>
        </button>

        <h3 style="font-size:16px;font-weight:700;color:#202223;margin:0 0 4px;">
            <i class="fa fa-share-alt" style="color:#303d89;margin-right:6px;"></i>
            Share Coupon
        </h3>
        <p id="share-coupon-info" style="font-size:13px;color:#6d7175;margin:0 0 20px;"></p>

        <label
            style="font-size:12px;font-weight:600;color:#6d7175;text-transform:uppercase;letter-spacing:.03em;display:block;margin-bottom:6px;">
            Mobile Number
        </label>
        <input type="text" id="share-mobile-input" maxlength="10" inputmode="numeric"
            placeholder="Enter 10-digit mobile number"
            style="width:100%;height:40px;border:1px solid #e3e5e8;border-radius:8px;padding:0 12px;font-size:14px;outline:none;font-family:inherit;margin-bottom:6px;"
            onkeydown="if(event.key==='Enter') sendCouponSms()">
        <p id="share-error-msg" style="font-size:12px;color:#b22222;margin:4px 0 14px;display:none;"></p>

        <button onclick="sendCouponSms()" id="share-send-btn"
            style="width:100%;background:#303d89;color:#fff;border:none;border-radius:8px;padding:10px;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:background .15s;">
            <i class="fa fa-paper-plane"></i>
            Send SMS
        </button>

    </div>
</div>

@include('admin.footer')

<script>
    function deleteCategory(id) {
        Swal.fire({
            title: 'Delete Category?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b22222',
            cancelButtonColor: '#6d7175',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/categories') }}/" + id,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    beforeSend: function () { Swal.showLoading(); },
                    success: function (res) {
                        Swal.fire('Deleted!', res.message, 'success');
                        $("#row" + id).fadeOut(300, function () { $(this).remove(); });
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong', 'error');
                    }
                });
            }
        });
    }

    // Show/hide parent category filter
    document.getElementById('typeFilter').addEventListener('change', function () {
        const div = document.getElementById('categoryFilterDiv');
        div.style.display = this.value === 'subcategory' ? '' : 'none';
    });

   var _shareCouponData = {};
   
    function openShareModal(id, code, discount, expiry) {
        _shareCouponData = { id, code, discount, expiry };
        document.getElementById('share-coupon-info').textContent =
            'Code: ' + code + '  |  Discount: ' + discount + '  |  Valid till: ' + expiry;
        document.getElementById('share-mobile-input').value = '';
        document.getElementById('share-error-msg').style.display = 'none';
        document.getElementById('share-coupon-modal').style.display = 'flex';
        setTimeout(() => document.getElementById('share-mobile-input').focus(), 100);
    }

    function closeShareModal() {
        document.getElementById('share-coupon-modal').style.display = 'none';
    }

    // Close on backdrop click
    document.getElementById('share-coupon-modal').addEventListener('click', function (e) {
        if (e.target === this) closeShareModal();
    });

    function sendCouponSms() {
        const mobile = document.getElementById('share-mobile-input').value.trim();
        const errEl = document.getElementById('share-error-msg');
        const btn = document.getElementById('share-send-btn');

        // Validate
        if (!/^[6-9]\d{9}$/.test(mobile)) {
            errEl.textContent = 'Please enter a valid 10-digit mobile number.';
            errEl.style.display = 'block';
            return;
        }

        errEl.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

        $.ajax({
            url: "{{ url('admin/coupons') }}/" + _shareCouponData.id + "/share",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                mobile: mobile,
            },
            success: function (res) {
                if (res.status) {
                    Swal.fire({ icon: 'success', title: 'SMS Sent!', text: res.message, timer: 2000, showConfirmButton: false });
                    closeShareModal();
                } else {
                    errEl.textContent = res.message ?? 'Failed to send SMS.';
                    errEl.style.display = 'block';
                }
            },
            error: function () {
                errEl.textContent = 'Something went wrong. Please try again.';
                errEl.style.display = 'block';
            },
            complete: function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-paper-plane"></i> Send SMS';
            }
        });
    }

</script>