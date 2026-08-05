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

        .order-detail-page {
            background: var(--bg);
            padding: 24px 28px;
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text-primary);
        }

        .order-detail-page * {
            box-sizing: border-box;
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 20px;
            font-weight: 650;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
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
            font-family: var(--font);
            transition: background .15s;
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
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none !important;
            font-family: var(--font);
            transition: background .15s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }

        .btn-secondary-dash:hover {
            background: var(--bg);
        }

        .detail-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:960px) {
            .detail-layout {
                grid-template-columns: 1fr;
            }
        }

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

        .order-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
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

        .pay-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .pay-paid {
            background: var(--green-bg);
            color: var(--green);
        }

        .pay-pending {
            background: var(--amber-bg);
            color: var(--amber);
        }

        .pay-failed {
            background: var(--red-bg);
            color: var(--red);
        }

        .pay-refunded {
            background: var(--purple-bg);
            color: var(--purple);
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .items-table thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--text-hint);
            padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
            text-align: left;
        }

        .items-table tbody tr {
            border-bottom: 1px solid var(--border);
        }

        .items-table tbody tr:last-child {
            border-bottom: none;
        }

        .items-table tbody tr:hover {
            background: #fafbfc;
        }

        .items-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
        }

        .items-table tfoot td {
            padding: 10px 16px;
            border-top: 1px solid var(--border);
            font-size: 13px;
        }

        .product-thumb {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .product-thumb-placeholder {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-sm);
            background: var(--bg);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-hint);
            font-size: 18px;
            flex-shrink: 0;
        }

        .product-name-cell {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 13px;
        }

        .product-variant {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 2px;
        }

        .product-sku {
            font-size: 11px;
            color: var(--text-hint);
            font-family: 'SF Mono', 'Fira Code', monospace;
            margin-top: 2px;
        }

        .qty-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 6px;
        }

        .price-cell {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .subtotal-cell {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid var(--bg);
            font-size: 13px;
        }

        .price-row:last-child {
            border-bottom: none;
        }

        .price-row .label {
            color: var(--text-secondary);
        }

        .price-row .value {
            font-weight: 600;
            color: var(--text-primary);
        }

        .price-row.total {
            padding-top: 12px;
            border-top: 2px solid var(--border);
            border-bottom: none;
            margin-top: 4px;
        }

        .price-row.total .label {
            font-size: 14px;
            font-weight: 650;
            color: var(--text-primary);
        }

        .price-row.total .value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .price-row.discount .value {
            color: var(--green);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 9px 0;
            border-bottom: 1px solid var(--bg);
            font-size: 13px;
            gap: 12px;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-row .info-label {
            color: var(--text-hint);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            flex-shrink: 0;
        }

        .info-row .info-value {
            font-weight: 500;
            color: var(--text-primary);
            text-align: right;
        }

        .customer-block {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .customer-avatar-lg {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .customer-name-lg {
            font-size: 14px;
            font-weight: 650;
            color: var(--text-primary);
        }

        .customer-email-sm {
            font-size: 12px;
            color: var(--text-hint);
            margin-top: 2px;
        }

        .customer-phone-sm {
            font-size: 12px;
            color: var(--text-hint);
            margin-top: 1px;
        }

        .address-block {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .address-block strong {
            color: var(--text-primary);
            font-weight: 600;
            display: block;
            margin-bottom: 3px;
        }

        .timeline {
            position: relative;
            padding-left: 24px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 6px;
            bottom: 6px;
            width: 2px;
            background: var(--border);
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 18px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -21px;
            top: 3px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid var(--surface);
            flex-shrink: 0;
        }

        .timeline-dot.active {
            background: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-light);
        }

        .timeline-dot.done {
            background: var(--green);
        }

        .timeline-dot.pending {
            background: var(--border);
        }

        .timeline-dot.cancelled {
            background: var(--red);
        }

        .timeline-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .timeline-time {
            font-size: 11.5px;
            color: var(--text-hint);
            margin-top: 2px;
        }

        .timeline-desc {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 3px;
        }

        .field-select-full {
            width: 100%;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 13px;
            color: var(--text-primary);
            background: var(--surface);
            outline: none;
            font-family: var(--font);
            transition: border-color .15s, box-shadow .15s;
        }

        .field-select-full:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        .field-input-full {
            width: 100%;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 13px;
            color: var(--text-primary);
            background: var(--surface);
            outline: none;
            font-family: var(--font);
            transition: border-color .15s, box-shadow .15s;
        }

        .field-input-full:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        .field-textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 12px;
            font-size: 13px;
            color: var(--text-primary);
            background: var(--surface);
            outline: none;
            resize: vertical;
            min-height: 80px;
            font-family: var(--font);
            transition: border-color .15s, box-shadow .15s;
        }

        .field-textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(48, 61, 137, .12);
        }

        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: .03em;
            display: block;
            margin-bottom: 6px;
        }

        .alert-success {
            background: var(--green-bg);
            color: var(--green);
            border: 1px solid #b2dfdb;
            border-radius: var(--radius-sm);
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        @media(max-width:768px) {
            .order-detail-page {
                padding: 16px;
            }
        }

            {
                {
                -- GST info rows hidden if 0 --
            }
        }

        .gst-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid var(--bg);
            font-size: 13px;
        }

        .gst-row .label {
            color: var(--text-secondary);
        }

        .gst-row .value {
            font-weight: 600;
            color: var(--text-primary);
        }
    </style>

    <div class="app-content content container-fluid">
        <div class="order-detail-page">

            {{-- Flash success --}}
            @if(session('success'))
                <div class="alert-success no-print">
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- ── Page header ── --}}
            <div class="page-header no-print">
                <div>
                    <h1>
                        Order
                        <span style="font-family:'SF Mono','Fira Code',monospace;color:var(--accent);font-size:18px">
                            #{{ $order->order_number }}
                        </span>
                    </h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        <a href="{{ route('admin.orders.index') }}">Orders</a>
                        <span>›</span>
                        Order Detail
                    </div>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                    <span class="pay-pill {{ $payClass }}">{{ ucfirst($order->payment_status) }}</span>
                    <span class="order-pill {{ $orderClass }}">{{ ucfirst($order->status) }}</span>

                    @if($order->invoice)
                        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn-secondary-dash">
                            <i class="fa fa-file-pdf-o"></i> Invoice
                        </a>
                    @endif

                    <a href="{{ route('admin.orders.index') }}" class="btn-secondary-dash">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="detail-layout">

                {{-- ══════════════ LEFT COLUMN ══════════════ --}}
                <div>

                    {{-- ── Order Items ── --}}
                    <div class="section-card">
                        <div class="section-card-header">
                            <h5>Order Items</h5>
                            <span style="font-size:12px;color:var(--text-hint)">
                                {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                            </span>
                        </div>
                        <div style="overflow-x:auto">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>HSN</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        @php
                                            // Thumbnail: variant image > product default image > null
                                            $thumb = null;
                                            if ($item->variant && $item->variant->image) {
                                                $thumb = asset('storage/' . $item->variant->image);
                                            } elseif ($item->product) {
                                                $thumb = $item->product->display_image;
                                            }

                                            $lineTotal = $item->price * $item->quantity;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="display:flex;align-items:center;gap:12px">
                                                    @if($thumb)
                                                        <img src="{{ $thumb }}" class="product-thumb"
                                                            alt="{{ $item->product_name ?? '' }}">
                                                    @else
                                                        <div class="product-thumb-placeholder"><i class="fa fa-image"></i></div>
                                                    @endif
                                                    <div>
                                                        <div class="product-name-cell">
                                                            {{ $item->product_name ?? ($item->product->name ?? 'Product') }}
                                                        </div>
                                                        @if($item->product?->weight)
                                                            <div class="product-variant">{{  $item->product?->weight }}ml</div>
                                                        @endif
                                                        @if($item->sku ?? ($item->product->sku ?? null))
                                                            <div class="product-sku">
                                                                SKU: {{ $item->sku ?? $item->product->sku }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span
                                                    style="font-size:12px;color:var(--text-secondary)">{{ $item->product?->hsn_code ?? '—' }}</span>
                                            </td>
                                            <td><span class="qty-chip">× {{ $item->quantity }}</span></td>
                                            <td><span class="price-cell">₹{{ number_format($item->price, 2) }}</span></td>
                                            <td><span class="subtotal-cell">₹{{ number_format($lineTotal, 2) }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                        {{-- Price breakdown --}}
                        <div style="padding:16px 20px;border-top:1px solid var(--border);background:#fafafa">
                            <div class="price-row">
                                <span class="label">Subtotal</span>
                                <span class="value">₹{{ number_format($order->subtotal, 2) }}</span>
                            </div>

                            @if($order->discount > 0)
                                <div class="price-row discount">
                                    <span class="label">
                                        Discount
                                        @if($order->coupon_code)
                                            <span
                                                style="font-family:'SF Mono','Fira Code',monospace;font-size:11px;background:var(--green-bg);color:var(--green);padding:1px 6px;border-radius:4px;margin-left:4px">
                                                {{ $order->coupon_code }}
                                            </span>
                                        @endif
                                    </span>
                                    <span class="value">− ₹{{ number_format($order->discount, 2) }}</span>
                                </div>
                            @endif

                            {{-- GST breakdown --}}
                            @if($order->tax_amount > 0)
                                @if($order->gst_type === 'igst' && $order->igst_amount > 0)
                                    <div class="price-row">
                                        <span class="label">IGST ({{ $order->igst_rate }}%)</span>
                                        <span class="value">₹{{ number_format($order->igst_amount, 2) }}</span>
                                    </div>
                                @else
                                    @if($order->cgst_amount > 0)
                                        <div class="price-row">
                                            <span class="label">CGST ({{ $order->cgst_rate }}%)</span>
                                            <span class="value">₹{{ number_format($order->cgst_amount, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($order->sgst_amount > 0)
                                        <div class="price-row">
                                            <span class="label">SGST ({{ $order->sgst_rate }}%)</span>
                                            <span class="value">₹{{ number_format($order->sgst_amount, 2) }}</span>
                                        </div>
                                    @endif
                                @endif
                            @endif

                            <div class="price-row total">
                                <span class="label">Total</span>
                                <span class="value">₹{{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Payment Information ── --}}
                    <div class="section-card">
                        <div class="section-card-header">
                            <h5>Payment Information</h5>
                        </div>
                        <div class="section-card-body" style="padding:16px 20px">
                            <div class="info-row">
                                <span class="info-label">Method</span>
                                <span class="info-value">
                                    {{ $order->payment_method ? (strtolower($order->payment_method) === 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) . '/Online Payment') : '—' }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status</span>
                                <span class="info-value">
                                    <span class="pay-pill {{ $payClass }}">{{ ucfirst($order->payment_status) }}</span>
                                </span>
                            </div>
                            @if($order->transaction_id)
                                <div class="info-row">
                                    <span class="info-label">Transaction ID</span>
                                    <span class="info-value"
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:12px">
                                        {{ $order->transaction_id }}
                                    </span>
                                </div>
                            @endif
                            @if($order->razorpay_payment_id)
                                <div class="info-row">
                                    <span class="info-label">Razorpay Payment</span>
                                    <span class="info-value"
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:12px">
                                        {{ $order->razorpay_payment_id }}
                                    </span>
                                </div>
                            @endif
                            @if($order->razorpay_order_id)
                                <div class="info-row">
                                    <span class="info-label">Razorpay Order</span>
                                    <span class="info-value"
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:12px">
                                        {{ $order->razorpay_order_id }}
                                    </span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label">Paid At</span>
                                <span class="info-value">{{ $order->updated_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Order Timeline ── --}}
                    <div class="section-card">
                        <div class="section-card-header">
                            <h5>Order Timeline</h5>
                        </div>
                        <div class="section-card-body">
                            <div class="timeline">

                                @foreach($order->statusHistory as $history)

                                    @php
                                        $dotClass = match ($history->status) {
                                            'pending' => 'done',
                                            'processing' => 'active',
                                            'shipped' => 'active',
                                            'delivered' => 'done',
                                            'cancelled' => 'cancelled',
                                            default => 'pending',
                                        };
                                    @endphp

                                    <div class="timeline-item">

                                        <div class="timeline-dot {{ $dotClass }}"></div>

                                        <div class="timeline-title">
                                            {{ ucfirst($history->status) }}
                                        </div>

                                        <div class="timeline-time">
                                            {{ $history->created_at->format('d M Y, h:i A') }}
                                        </div>


                                        @if($history->remarks)
                                            <div class="timeline-desc">
                                                {{ $history->remarks }}
                                            </div>
                                        @endif

                                    </div>

                                @endforeach

                            </div>

                        </div>
                    </div>

                </div>{{-- /left column --}}

                {{-- ══════════════ RIGHT COLUMN ══════════════ --}}
                <div>

                    {{-- ── Update Status ── --}}
                    @if($order->status !== 'cancelled' && $order->status !== 'delivered')
                        <div class="section-card no-print">
                            <div class="section-card-header">
                                <h5>Update Status</h5>
                            </div>
                            <div class="section-card-body">
                                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div style="margin-bottom:12px">
                                        <label class="field-label">Order Status</label>
                                        <select name="status" class="field-select-full">
                                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                                    {{ ucfirst($s) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="shipping-details"
                                        style="{{ in_array($order->status, ['shipped', 'delivered']) ? '' : 'display:none' }}">

                                        <div style="margin-bottom:12px">
                                            <label class="field-label">
                                                Courier
                                            </label>

                                            <select name="courier_id" class="field-select-full">

                                                <option value="">
                                                    Select Courier
                                                </option>

                                                @foreach($couriers as $courier)

                                                    <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                                                        {{ $courier->name }}
                                                    </option>

                                                @endforeach

                                            </select>
                                        </div>

                                        <div style="margin-bottom:12px">
                                            <label class="field-label">
                                                Tracking Number
                                            </label>

                                            <input type="text" name="tracking_number" class="field-input-full"
                                                value="{{ $order->tracking_number }}" placeholder="Enter tracking number">
                                        </div>

                                    </div>


                                    <div style="margin-bottom:14px">
                                        <label class="field-label">Note (optional)</label>
                                        <textarea name="note" class="field-textarea"
                                            placeholder="Add a note for this status update…"></textarea>
                                    </div>
                                    <button type="submit" class="btn-primary-dash"
                                        style="width:100%;justify-content:center">
                                        <i class="fa fa-refresh"></i> Update Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- ── Customer Info ── --}}
                    <div class="section-card">
                        <div class="section-card-header">
                            <h5>Customer</h5>
                            @if($order->customer_id)
                                <a href="{{ route('admin.customers.show', $order->customer_id) }}"
                                    style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:500">
                                    View Profile →
                                </a>
                            @endif
                        </div>
                        <div class="section-card-body">
                            @php
                                $words2 = explode(' ', trim($order->customer_name));
                                $initials2 = strtoupper(substr($words2[0] ?? '', 0, 1) . substr($words2[1] ?? '', 0, 1));
                            @endphp
                            <div class="customer-block">
                                <div class="customer-avatar-lg"
                                    style="background:var(--accent-light);color:var(--accent)">
                                    {{ $initials2 }}
                                </div>
                                <div>
                                    <div class="customer-name-lg">{{ $order->customer_name }}</div>
                                    <div class="customer-email-sm">{{ $order->customer_email }}</div>
                                    @if($order->customer_phone)
                                        <div class="customer-phone-sm">{{ $order->customer_phone }}</div>
                                    @endif
                                </div>
                            </div>
                            @if($customerOrderCount !== null)
                                <div class="info-row">
                                    <span class="info-label">Total Orders</span>
                                    <span class="info-value">{{ $customerOrderCount }}</span>
                                </div>
                            @endif
                            @if($order->customer)
                                <div class="info-row">
                                    <span class="info-label">Member Since</span>
                                    <span class="info-value">{{ $order->customer->created_at->format('M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── Shipping Address ── --}}
                    <div class="section-card">
                        <div class="section-card-header">
                            <h5>Shipping Address</h5>
                        </div>
                        <div class="section-card-body">
                            <div class="address-block">
                                <strong>{{ $order->customer_name }}</strong>
                                @if($order->address_line_1) {{ $order->address_line_1 }}<br> @endif
                                @if($order->address_line_2) {{ $order->address_line_2 }}<br> @endif
                                @if($order->city) {{ $order->city->name }}, @endif
                                @if($order->state) {{ $order->state->name }}<br> @endif
                                @if($order->pincode) {{ $order->pincode }}<br> @endif
                                India
                                @if($order->customer_phone)
                                    <br><span style="color:var(--text-hint);font-size:12px">
                                        📞 {{ $order->customer_phone }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── Order Info ── --}}
                    <div class="section-card">
                        <div class="section-card-header">
                            <h5>Order Info</h5>
                        </div>
                        <div class="section-card-body" style="padding:14px 20px">
                            <div class="info-row">
                                <span class="info-label">Order ID</span>
                                <span class="info-value"
                                    style="font-family:'SF Mono','Fira Code',monospace;color:var(--accent);font-size:12px">
                                    #{{ $order->order_number }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Placed On</span>
                                <span class="info-value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Payment</span>
                                <span class="info-value">
                                    {{ $order->payment_method ? (strtolower($order->payment_method) === 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) . '/Online Payment') : '—' }}</span>
                            </div>
                            @if($order->courier)

                                <div class="info-row">
                                    <span class="info-label">Courier</span>

                                    <span class="info-value">
                                        {{ $order->courier->name }}
                                    </span>
                                </div>

                            @endif
                            @if($order->tracking_number)

                                <div class="info-row">
                                    <span class="info-label">Tracking Number</span>

                                    <span class="info-value"
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:12px">
                                        {{ $order->tracking_number }}
                                    </span>
                                </div>

                            @endif

                            @if($order->courier && $order->courier->website_url)

                                <div class="info-row">
                                    <span class="info-label">Tracking Site</span>

                                    <span class="info-value">
                                        <a href="{{ $order->courier->website_url }}" target="_blank"
                                            style="color:var(--accent)">
                                            Open Tracking Site
                                        </a>
                                    </span>
                                </div>

                            @endif

                            @if($order->coupon_code)
                                <div class="info-row">
                                    <span class="info-label">Coupon</span>
                                    <span class="info-value"
                                        style="font-family:'SF Mono','Fira Code',monospace;color:var(--green);font-size:12px">
                                        {{ $order->coupon_code }}
                                    </span>
                                </div>
                            @endif
                            @if($order->invoice)
                                <div class="info-row">
                                    <span class="info-label">Invoice #</span>
                                    <span class="info-value"
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:12px">
                                        {{ $order->invoice->invoice_number }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>{{-- /right column --}}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const statusSelect = document.querySelector('[name="status"]');
        const shippingBlock = document.getElementById('shipping-details');

        function toggleShippingFields() {

            if (
                statusSelect.value === 'shipped' ||
                statusSelect.value === 'delivered'
            ) {
                shippingBlock.style.display = 'block';
            } else {
                shippingBlock.style.display = 'none';
            }
        }

        toggleShippingFields();

        statusSelect.addEventListener('change', toggleShippingFields);

    });
</script>

@include('admin.footer')