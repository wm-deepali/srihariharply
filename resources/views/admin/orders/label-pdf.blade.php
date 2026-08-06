<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shipping Labels</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #202223;
        }

        /* ── One A4 sheet per page ── */
        .a4-page {
            page-break-after: always;
            padding: 8mm;
        }

        .a4-page:last-child {
            page-break-after: auto;
        }

        .grid-table {
            width: 100%;
            /* height: 100%; */
            border-collapse: collapse;
            table-layout: fixed;
        }

        .grid-table td {
            vertical-align: top;
            padding: 4mm;
        }

        /* ── Label card (compact, fits any grid cell) ── */
        .label-card {
            border: 1.5px solid #e3e5e8;
            border-radius: 8px;
            overflow: hidden;
            /* height: 100%; */
        }

        .lbl-header {
            background: #303d89;
            color: #fff;
            padding: 8px 10px;
        }

        .brand-name {
            font-size: 11px;
            font-weight: 700;
        }

        .brand-tagline {
            font-size: 8px;
            color: rgba(255, 255, 255, .7);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-top: 2px;
        }

        .lbl-order-number {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 11px;
            margin-top: 4px;
        }

        .lbl-body {
            padding: 10px;
        }

        .meta-heading {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #8c9196;
            border-bottom: 1px solid #e3e5e8;
            padding-bottom: 4px;
            margin-bottom: 5px;
        }

        .ship-name {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .ship-address {
            font-size: 10px;
            line-height: 1.5;
        }

        .pincode-pill {
            display: inline-block;
            background: #f0f1fc;
            color: #303d89;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            margin-top: 6px;
            font-family: 'Courier New', monospace;
        }

        .ship-phone {
            font-size: 9.5px;
            color: #6d7175;
            margin-top: 5px;
        }

        .lbl-footer-row {
            margin-top: 8px;
            border-top: 1px solid #e3e5e8;
            padding-top: 6px;
        }

        .footer-label {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #8c9196;
        }

        .footer-value {
            font-size: 9.5px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    @php $cellWidth = 100 / $cols; @endphp

    @foreach($pages as $page)
        <div class="a4-page">
            <table class="grid-table">
                @foreach($page->chunk($cols) as $row)
                    <tr>
                        @foreach($row as $order)
                            <td style="width: {{ $cellWidth }}%;">
                                <div class="label-card">
                                    <div class="lbl-header">
                                        <div class="brand-name">{{ $setting->company_name ?? config('app.name') }}</div>
                                        <div class="brand-tagline">Shipping Label</div>
                                        <div class="lbl-order-number">#{{ $order->order_number }}</div>
                                    </div>
                                    <div class="lbl-body">
                                        <div class="meta-heading">Ship To</div>
                                        <div class="ship-name">{{ $order->customer_name }}</div>
                                        <div class="ship-address">
                                            {{ $order->address_line_1 }}<br>
                                            @if($order->address_line_2)
                                                {{ $order->address_line_2 }}<br>
                                            @endif
                                            {{ optional($order->city)->name }}, {{ optional($order->state)->name }}
                                        </div>
                                        <div class="pincode-pill">PIN: {{ $order->pincode }}</div>
                                        <div class="ship-phone">Ph: {{ $order->customer_phone }}</div>
                                        <div class="lbl-footer-row">
                                            <div class="footer-label">Courier</div>
                                            <div class="footer-value">{{ optional($order->courier)->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endforeach

                        {{-- Empty cells fill karo agar row incomplete hai --}}
                        @if($row->count() < $cols)
                            @for($i = 0; $i < $cols - $row->count(); $i++)
                                <td style="width: {{ $cellWidth }}%;"></td>
                            @endfor
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach

</body>
</html>