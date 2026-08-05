<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 13px;
            color: #202223;
            background: #d6dbd9;
            padding: 32px 20px;
        }

        /* ── Print / Action Bar ── */
        .print-bar {
            max-width: 820px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            color: #1F5552;
            border: 1px solid #b0c8c6;
            border-radius: 6px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #1F5552;
            color: #f4f5f2;
            border: none;
            border-radius: 6px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(31, 85, 82, .30);
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff;
            color: #202223;
            border: 1px solid #d4dbd9;
            border-radius: 6px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        /* ── Wrapper ── */
        .invoice-wrap {
            max-width: 820px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 6px 32px rgba(0, 0, 0, .12);
        }

        /* ── Header Band ── */
        .inv-header {
            background: #1F5552;
            padding: 0;
        }

        .inv-header-inner {
            display: table;
            width: 100%;
        }

        .inv-header-left {
            display: table-cell;
            vertical-align: top;
            padding: 36px 40px 36px 40px;
            width: 55%;
        }

        .inv-header-right {
            display: table-cell;
            vertical-align: top;
            padding: 36px 40px 36px 40px;
            text-align: right;
            background: rgba(0, 0, 0, 0.10);
            width: 45%;
        }

        /* Logo area */
        .logo-wrap {
            margin-bottom: 16px;
        }

        /* Brand name — big and prominent */
        .brand-name {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #f4f5f2;
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .brand-tagline {
            font-size: 11px;
            color: rgba(244, 245, 242, 0.55);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .brand-address {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 14px;
            margin-top: 2px;
        }

        /* Invoice label + number */
        .inv-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(244, 245, 242, 0.45);
            margin-bottom: 6px;
        }

        .inv-number {
            font-size: 22px;
            font-weight: 700;
            color: #f4f5f2;
            font-family: 'Courier New', monospace;
            line-height: 1.2;
            margin-bottom: 8px;
            white-space: nowrap;
            word-break: keep-all;
        }

        .inv-date {
            font-size: 12px;
            color: rgba(244, 245, 242, 0.65);
            margin-bottom: 14px;
        }

        /* Status badge */
        .inv-status-badge {
            display: inline-block;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .badge-paid {
            background: #d4f0e8;
            color: #0a6644;
        }

        .badge-pending {
            background: #fff3cc;
            color: #7a5500;
        }

        .badge-failed {
            background: #fde8e8;
            color: #9b1c1c;
        }

        .badge-refunded {
            background: #ede9fe;
            color: #5b21b6;
        }

        /* ── Divider stripe ── */
        .inv-stripe {
            height: 4px;
            background: linear-gradient(90deg, #163e3c 0%, #2a7a75 50%, #163e3c 100%);
        }

        /* ── Body ── */
        .inv-body {
            padding: 36px 40px 32px;
        }

        /* ── Bill To / Order Info ── */
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 32px;
        }

        .meta-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .meta-col:last-child {
            padding-left: 32px;
            border-left: 1px solid #e6eae9;
        }

        .meta-heading {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: #1F5552;
            margin-bottom: 10px;
            padding-bottom: 7px;
            border-bottom: 2px solid #1F5552;
        }

        .meta-name {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .meta-detail {
            font-size: 12.5px;
            color: #555;
            line-height: 1.8;
        }

        .meta-info-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .meta-info-label {
            display: table-cell;
            font-size: 10.5px;
            color: #8a9896;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: 100px;
            vertical-align: top;
            padding-top: 1px;
        }

        .meta-info-value {
            display: table-cell;
            font-size: 12.5px;
            font-weight: 600;
            color: #202223;
        }

        /* ── GST Pill ── */
        .gst-summary {
            margin-bottom: 16px;
        }

        .gst-pill {
            display: inline-block;
            background: #e6f0ef;
            color: #1F5552;
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── Items Table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 0;
        }

        .items-table thead tr {
            background: #1F5552;
        }

        .items-table thead th {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #a8c4c2;
            padding: 11px 16px;
            text-align: left;
        }

        .items-table thead th.right {
            text-align: right;
        }

        .items-table thead th.center {
            text-align: center;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #edf0ef;
        }

        .items-table tbody tr:nth-child(even) {
            background: #fafbfa;
        }

        .items-table tbody tr:last-child {
            border-bottom: 2px solid #d0d8d7;
        }

        .items-table tbody td {
            padding: 14px 16px;
            vertical-align: top;
        }

        .items-table tbody td.right {
            text-align: right;
        }

        .items-table tbody td.center {
            text-align: center;
        }

        .item-name {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 3px;
            font-size: 13.5px;
        }

        .item-variant {
            font-size: 11px;
            color: #7a9e9c;
            margin-top: 2px;
        }

        .item-sku {
            font-size: 10.5px;
            color: #b0bdb9;
            font-family: 'Courier New', monospace;
            margin-top: 2px;
        }

        /* ── Totals Block ── */
        .totals-wrap {
            display: table;
            width: 100%;
            margin-top: 0;
        }

        .totals-left {
            display: table-cell;
            width: 52%;
            vertical-align: bottom;
            padding-right: 24px;
        }

        .totals-right {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-left: 8px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 14px;
            font-size: 13px;
        }

        .totals-table .t-label {
            color: #555;
        }

        .totals-table .t-value {
            text-align: right;
            font-weight: 600;
            color: #202223;
        }

        .totals-table .t-discount .t-label,
        .totals-table .t-discount .t-value {
            color: #1a7a5e;
        }

        .totals-table .t-divider td {
            padding: 0;
            border-top: 1px solid #d4dbd9;
        }

        .totals-table .t-total td {
            border-top: 3px solid #1F5552;
            padding-top: 12px;
            padding-bottom: 12px;
            font-size: 16px;
            font-weight: 700;
        }

        .totals-table .t-total .t-label {
            color: #1F5552;
            font-size: 15px;
        }

        .totals-table .t-total .t-value {
            color: #1F5552;
            font-size: 18px;
        }

        /* ── Notes / T&C Box ── */
        .inv-notes {
            margin-top: 28px;
            padding: 16px 20px;
            background: #f4f5f2;
            border-radius: 4px;
            border-left: 4px solid #1F5552;
        }

        .inv-notes p {
            font-size: 12px;
            color: #555;
            line-height: 1.7;
        }

        .inv-notes strong {
            color: #1F5552;
            display: block;
            font-size: 10.5px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        /* ── Footer Band ── */
        .inv-footer {
            background: #163e3c;
            border-top: 3px solid #1F5552;
            padding: 18px 40px;
            text-align: center;
        }

        .inv-footer p {
            font-size: 11.5px;
            color: rgba(244, 245, 242, 0.55);
            line-height: 1.7;
        }

        .inv-footer strong {
            color: #a8c4c2;
        }

        /* ── Print ── */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .print-bar {
                display: none;
            }

            .invoice-wrap {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

    {{-- ── Action bar (browser only) ── --}}
    @if(!isset($isPdf) || !$isPdf)
        <div class="print-bar">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn-back">
                ← Back to Order
            </a>
            <div style="display:flex;gap:8px;">
                <button onclick="window.print()" class="btn-print">
                    🖨 Print
                </button>
                <a href="{{ route('admin.orders.invoice.download', $order) }}" class="btn-pdf">
                    ⬇ Download PDF
                </a>
            </div>
        </div>
    @endif

    <div class="invoice-wrap">

        {{-- ── Header ── --}}
        <div class="inv-header">
            <div class="inv-header-inner">

                {{-- Left: Brand --}}
                <div class="inv-header-left">

                    @if($logo_64)
                        <div class="logo-wrap">
                            <img src="{{ $logo_64 }}" style="max-height:64px;max-width:200px;">
                        </div>
                    @endif

                    <div class="brand-name">
                        {{ $setting->company_name ?? config('app.name') }}
                    </div>

                    <div class="brand-tagline">Tax Invoice</div>

                    <div class="brand-address">

                        @if($setting?->company_address || $setting?->city || $setting?->state || $setting?->company_pincode)
                            <div style="display:table;width:100%;margin-bottom:6px;">
                                <div style="display:table-cell;width:18px;vertical-align:top;padding-top:1px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="rgba(244,245,242,0.6)" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                </div>
                                <div
                                    style="display:table-cell;vertical-align:top;font-size:12px;color:rgba(244,245,242,0.75);line-height:1.7;">
                                    @if($setting?->company_address){{ $setting->company_address }},@endif
                                    @if($setting?->city || $setting?->state || $setting?->company_pincode)
                                        {{ optional($setting->city)->name ?? '' }}@if($setting?->city && $setting?->state),
                                        @endif{{ optional($setting->state)->name ?? '' }}@if($setting?->company_pincode) &ndash;
                                        {{ $setting->company_pincode }}@endif
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($setting?->company_gstin)
                            <div style="display:table;width:100%;margin-bottom:6px;">
                                <div style="display:table-cell;width:18px;vertical-align:middle;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="rgba(244,245,242,0.6)" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" />
                                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                    </svg>
                                </div>
                                <div
                                    style="display:table-cell;vertical-align:middle;font-size:12px;color:rgba(244,245,242,0.75);">
                                    GSTIN: <span
                                        style="font-family:'Courier New',monospace;letter-spacing:0.04em;">{{ $setting->company_gstin }}</span>
                                </div>
                            </div>
                        @endif

                        @if($setting?->company_pan)
                            <div style="display:table;width:100%;margin-bottom:6px;">
                                <div style="display:table-cell;width:18px;vertical-align:middle;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="rgba(244,245,242,0.6)" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="2" y="5" width="20" height="14" rx="2" />
                                        <line x1="2" y1="10" x2="22" y2="10" />
                                    </svg>
                                </div>
                                <div
                                    style="display:table-cell;vertical-align:middle;font-size:12px;color:rgba(244,245,242,0.75);">
                                    PAN: <span
                                        style="font-family:'Courier New',monospace;letter-spacing:0.04em;">{{ $setting->company_pan }}</span>
                                </div>
                            </div>
                        @endif

                        @if($setting?->company_phone)
                            <div style="display:table;width:100%;margin-bottom:6px;">
                                <div style="display:table-cell;width:18px;vertical-align:middle;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="rgba(244,245,242,0.6)" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.62 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-.85a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 17z" />
                                    </svg>
                                </div>
                                <div
                                    style="display:table-cell;vertical-align:middle;font-size:12px;color:rgba(244,245,242,0.75);">
                                    {{ $setting->company_phone }}
                                </div>
                            </div>
                        @endif

                        @if($setting?->company_email)
                            <div style="display:table;width:100%;margin-bottom:0;">
                                <div style="display:table-cell;width:18px;vertical-align:middle;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="rgba(244,245,242,0.6)" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <polyline points="22,6 12,13 2,6" />
                                    </svg>
                                </div>
                                <div
                                    style="display:table-cell;vertical-align:middle;font-size:12px;color:rgba(244,245,242,0.75);">
                                    {{ $setting->company_email }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Right: Invoice Details --}}
                <div class="inv-header-right">
                    <div class="inv-label">Invoice</div>
                    <div class="inv-number">#{{ $invoice->invoice_number }}</div>
                    <div class="inv-date">
                        Date: {{ optional($invoice->invoice_date)->format($setting->invoice_date_format ?? 'd M Y') }}
                    </div>
                    @php
                        $badgeClass = match ($order->payment_status) {
                            'paid' => 'badge-paid',
                            'pending' => 'badge-pending',
                            'failed' => 'badge-failed',
                            'refunded' => 'badge-refunded',
                            default => 'badge-pending',
                        };
                    @endphp
                    <div>
                        <span class="inv-status-badge {{ $badgeClass }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Stripe ── --}}
        <div class="inv-stripe"></div>

        {{-- ── Body ── --}}
        <div class="inv-body">

            {{-- Bill To + Order Info --}}
            <div class="meta-row">
                <div class="meta-col">
                    <div class="meta-heading">Bill To</div>
                    <div class="meta-name">{{ $invoice->customer_name }}</div>
                    <div class="meta-detail">
                        {{ $invoice->billing_address }}<br>
                        {{ $invoice->customer_email }}<br>
                        @if($invoice->customer_phone) {{ $invoice->customer_phone }} @endif
                    </div>
                </div>

                <div class="meta-col">
                    <div class="meta-heading">Order Details</div>

                    <div class="meta-info-row">
                        <div class="meta-info-label">Order #</div>
                        <div class="meta-info-value" style="font-family:'Courier New',monospace;color:#1F5552;">
                            {{ $order->order_number }}
                        </div>
                    </div>
                    <div class="meta-info-row">
                        <div class="meta-info-label">Order Date</div>
                        <div class="meta-info-value">{{ $order->created_at->format('d M Y') }}</div>
                    </div>
                    @if($order->payment_method)
                        <div class="meta-info-row">
                            <div class="meta-info-label">Payment</div>
                            <div class="meta-info-value">
                                {{ strtolower($order->payment_method) === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}
                            </div>
                        </div>
                    @endif
                    @if($order->transaction_id)
                        <div class="meta-info-row">
                            <div class="meta-info-label">Txn ID</div>
                            <div class="meta-info-value" style="font-family:'Courier New',monospace;font-size:11px;">
                                {{ $order->transaction_id }}
                            </div>
                        </div>
                    @endif
                    @if($order->coupon_code)
                        <div class="meta-info-row">
                            <div class="meta-info-label">Coupon</div>
                            <div class="meta-info-value" style="color:#1a7a5e;font-family:'Courier New',monospace;">
                                {{ $order->coupon_code }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- GST Badge --}}
            @if($invoice->gst_type)
                <div class="gst-summary">
                    <span class="gst-pill">{{ strtoupper($invoice->gst_type) }} Applied</span>
                </div>
            @endif

            {{-- Items Table --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:36px;">#</th>
                        <th>Item Description</th>
                        <th class="center" style="width:70px;">HSN</th>
                        <th class="center" style="width:70px;">Qty</th>
                        <th class="right" style="width:120px;">Unit Price</th>
                        <th class="right" style="width:120px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $i => $item)
                        @php $lineTotal = $item->price * $item->quantity; @endphp
                        <tr>
                            <td style="color:#8a9896;font-size:12px;">{{ $i + 1 }}</td>
                            <td>
                                <div class="item-name">
                                    {{ $item->product_name ?? ($item->product->name ?? 'Product') }}
                                </div>
                                @if($item->product->weight)
                                    <div class="item-variant">{{ $item->product->weight }}ml</div>
                                @endif
                                @if($item->sku ?? ($item->product->sku ?? null))
                                    <div class="item-sku">SKU: {{ $item->sku ?? $item->product->sku }}</div>
                                @endif
                            </td>
                            <td class="center" style="font-size:12px;color:#555;">{{ $item->product?->hsn_code ?? '—' }}
                            </td>
                            <td class="center">{{ $item->quantity }}</td>
                            <td class="right">&#8377;{{ number_format($item->price, 2) }}</td>
                            <td class="right" style="font-weight:600;">&#8377;{{ number_format($lineTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="totals-wrap">
                <div class="totals-left">
                    @if($order->coupon_code)
                        <div class="inv-notes" style="margin-top:20px;">
                            <p>
                                <strong>Discount Applied</strong>
                                Coupon <strong
                                    style="color:#1F5552;font-size:13px;letter-spacing:0;">{{ $order->coupon_code }}</strong>
                                saved you &#8377;{{ number_format($invoice->discount, 2) }} on this order.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="totals-right">
                    <table class="totals-table" style="margin-top:16px;">
                        <tr>
                            <td class="t-label">Subtotal</td>
                            <td class="t-value">&#8377;{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>

                        @if($invoice->discount > 0)
                            <tr class="t-discount">
                                <td class="t-label">Discount</td>
                                <td class="t-value">− &#8377;{{ number_format($invoice->discount, 2) }}</td>
                            </tr>
                        @endif

                        @if($setting?->show_gst_breakup)
                            @if($invoice->tax_amount > 0)
                                @if($invoice->gst_type === 'igst' && $invoice->igst_amount > 0)
                                    <tr>
                                        <td class="t-label">IGST ({{ $invoice->igst_rate }}%)</td>
                                        <td class="t-value">&#8377;{{ number_format($invoice->igst_amount, 2) }}</td>
                                    </tr>
                                @else
                                    @if($invoice->cgst_amount > 0)
                                        <tr>
                                            <td class="t-label">CGST ({{ $invoice->cgst_rate }}%)</td>
                                            <td class="t-value">&#8377;{{ number_format($invoice->cgst_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if($invoice->sgst_amount > 0)
                                        <tr>
                                            <td class="t-label">SGST ({{ $invoice->sgst_rate }}%)</td>
                                            <td class="t-value">&#8377;{{ number_format($invoice->sgst_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endif
                        @else
                            @if($invoice->tax_amount > 0)
                                <tr>
                                    <td class="t-label">GST</td>
                                    <td class="t-value">&#8377;{{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                            @endif
                        @endif

                        <tr class="t-divider">
                            <td colspan="2"></td>
                        </tr>
                        <tr class="t-total">
                            <td class="t-label">Grand Total</td>
                            <td class="t-value">&#8377;{{ number_format($invoice->grand_total, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Terms & Conditions --}}
            @if($setting?->terms_conditions)
                <div class="inv-notes" style="margin-top:28px;">
                    <p>
                        <strong>Terms &amp; Conditions</strong>
                        {!! nl2br(e($setting->terms_conditions)) !!}
                    </p>
                </div>
            @endif

        </div>{{-- /inv-body --}}

        {{-- ── Footer ── --}}
        <div class="inv-footer">
            <p>
                <strong>{{ $setting->company_name ?? config('app.name') }}</strong>
                @if($setting?->company_gstin)
                    &nbsp;·&nbsp; GSTIN: {{ $setting->company_gstin }}
                @endif
                @if($setting?->company_email)
                    &nbsp;·&nbsp; {{ $setting->company_email }}
                @endif
                @if($setting?->company_phone)
                    &nbsp;·&nbsp; {{ $setting->company_phone }}
                @endif
                &nbsp;·&nbsp; Invoice #{{ $invoice->invoice_number }}
            </p>
            <p style="margin-top:5px;font-size:11px;">
                This invoice was generated on
                {{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} IST
            </p>
        </div>

    </div>{{-- /invoice-wrap --}}

</body>

</html>