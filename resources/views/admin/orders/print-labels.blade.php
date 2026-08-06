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
            --blue: #0069d9;
            --blue-bg: #e8f2ff;
            --purple: #6d28d9;
            --purple-bg: #ede9fe;
            --radius-sm: 8px;
            --radius-md: 12px;
            --shadow-card: 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 1px var(--border);
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .orders-page {
            background: var(--bg);
            padding: 24px 28px;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text-primary);
        }

        .orders-page * {
            box-sizing: border-box;
        }

        .orders-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .orders-page-header h1 {
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

        .btn-primary-dash,
        .btn-secondary-dash,
        .btn-filter-search,
        .btn-filter-reset {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: var(--radius-sm);
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: background .15s;
            border: none;
        }

        .btn-primary-dash {
            background: var(--accent);
            color: #fff !important;
        }

        .btn-primary-dash:hover {
            background: #252f70;
        }

        .btn-secondary-dash {
            background: var(--surface);
            color: var(--text-primary) !important;
            border: 1px solid var(--border);
            font-weight: 500;
        }

        .btn-secondary-dash:hover {
            background: var(--bg);
        }

        .btn-filter-search {
            background: var(--accent);
            color: #fff;
            height: 36px;
        }

        .btn-filter-reset {
            background: var(--surface);
            color: var(--text-primary);
            border: 1px solid var(--border);
            height: 36px;
            font-weight: 500;
        }

        .orders-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .filter-bar {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }

        .filter-row {
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
            min-width: 150px;
        }

        .filter-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .label-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: end;
            flex-wrap: wrap;
            gap: 14px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
        }

        .orders-table-wrap {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            font-family: var(--font);
        }

        .orders-table thead th {
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

        .orders-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .1s;
        }

        .orders-table tbody tr:last-child {
            border-bottom: none;
        }

        .orders-table tbody tr:hover {
            background: #fafbfc;
        }

        .orders-table tbody td {
            padding: 13px 16px;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .order-id {
            font-size: 13px;
            font-weight: 700;
            color: var(--accent);
            font-family: 'SF Mono', 'Fira Code', monospace;
        }

        .customer-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .date-cell {
            font-size: 12.5px;
            color: var(--text-secondary);
        }

        .order-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .order-new {
            background: var(--blue-bg);
            color: var(--blue);
        }

        .order-processing {
            background: var(--amber-bg);
            color: var(--amber);
        }

        .order-shipped {
            background: var(--purple-bg);
            color: var(--purple);
        }

        .order-delivered {
            background: var(--green-bg);
            color: var(--green);
        }

        .order-cancelled {
            background: var(--red-bg);
            color: var(--red);
        }

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

        .selected-count {
            font-size: 12.5px;
            color: var(--text-hint);
        }

        .selected-count strong {
            color: var(--accent);
        }

        @media(max-width:768px) {
            .orders-page {
                padding: 16px;
            }

            .filter-row,
            .label-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-control {
                min-width: 100%;
            }
        }
    </style>

    <div class="app-content content container-fluid">
        <div class="orders-page">

            {{-- ── Page header ── --}}
            <div class="orders-page-header">
                <div>
                    <h1>Print Label</h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        <a href="{{ route('admin.orders.index') }}">Manage Orders</a>
                        <span>›</span>
                        Print Label
                    </div>
                </div>
            </div>

            {{-- ── Main card ── --}}
            <div class="orders-card">

                {{-- Date filter bar --}}
                <div class="filter-bar">
                    <form method="GET" action="{{ route('admin.orders.print-labels') }}">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label>From Date</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}"
                                    class="filter-control" style="min-width:140px">
                            </div>
                            <div class="filter-group">
                                <label>To Date</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}"
                                    class="filter-control" style="min-width:140px">
                            </div>
                            <div class="filter-actions">
                                <button type="submit" class="btn-filter-search">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.orders.print-labels') }}" class="btn-filter-reset">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Print form wraps toolbar + table --}}
                <form method="POST" action="{{ route('admin.orders.preview-labels') }}" id="labelForm">
                    @csrf

                    {{-- Toolbar: label size + print button --}}
                    <div class="label-toolbar">
                        <div class="filter-group">
                            <label>Label Size</label>
                            <select name="label_size" required class="filter-control" style="min-width:120px">
                                <option value="A4">A4</option>
                                <option value="A5">A5</option>
                                <option value="A6" selected>A6</option>
                                <option value="A8">A8</option>
                            </select>
                        </div>

                        <div style="display:flex;align-items:center;gap:14px">
                            <span class="selected-count" id="selectedCount">
                                <strong>0</strong> orders selected
                            </span>
                            <button type="submit" id="printLabelBtn" class="btn-primary-dash">
                                <i class="fa fa-eye"></i> Preview Labels
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="orders-table-wrap">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th style="width:36px"><input type="checkbox" id="selectAll"></th>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>City / State</th>
                                    <th>Pincode</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $orderClass = match ($order->status) {
                                            'new' => 'order-new',
                                            'processing' => 'order-processing',
                                            'shipped' => 'order-shipped',
                                            'delivered' => 'order-delivered',
                                            'cancelled' => 'order-cancelled',
                                            default => 'order-new',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                                                class="order-checkbox">
                                        </td>
                                        <td><span class="order-id">#{{ $order->order_number }}</span></td>
                                        <td><span class="customer-name">{{ $order->customer_name }}</span></td>
                                        <td class="date-cell">
                                            {{ optional($order->city)->name }}, {{ optional($order->state)->name }}
                                        </td>
                                        <td class="date-cell">{{ $order->pincode }}</td>
                                        <td>
                                            <span class="order-pill {{ $orderClass }}">{{ ucfirst($order->status) }}</span>
                                        </td>
                                        <td>
                                            <div class="date-cell">
                                                {{ $order->created_at->format('d M Y') }}
                                                <small>{{ $order->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <div class="empty-icon"><i class="fa fa-tags"></i></div>
                                                <p
                                                    style="font-size:15px;font-weight:600;color:var(--text-primary);margin:0 0 6px">
                                                    No orders found
                                                </p>
                                                <p style="font-size:13px;color:var(--text-hint);margin:0">
                                                    Try adjusting the date range filter.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

            </div>{{-- /.orders-card --}}
        </div>{{-- /.orders-page --}}
    </div>
</div>

@include('admin.footer')

<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.order-checkbox');
    const countLabel = document.getElementById('selectedCount').querySelector('strong');

    function updateCount() {
        const checked = document.querySelectorAll('.order-checkbox:checked').length;
        countLabel.textContent = checked;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateCount();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

    document.getElementById('labelForm').addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        if (checked.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No Order Selected',
                text: 'Kripya kam se kam ek order select karein Label print karne ke liye.'
            });
        }
    });
</script>