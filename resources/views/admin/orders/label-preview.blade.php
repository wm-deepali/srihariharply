@include('admin.top-header')
<div class="main-section">
    @include('admin.header')

    <style>
    :root {
        --bg: #f1f2f4;
        --surface: #ffffff;
        --border: #e3e5e8;
        --text-primary: #202223;
        --text-secondary:#6d7175;
        --text-hint: #8c9196;
        --accent: #303d89;
        --accent-light: #f0f1fc;
        --radius-sm: 8px;
        --radius-md: 12px;
        --shadow-card: 0 1px 3px rgba(0,0,0,.08), 0 0 0 1px var(--border);
        --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    .orders-page { background: var(--bg); padding: 24px 28px; min-height: 100vh; font-family: var(--font); color: var(--text-primary); }
    .orders-page * { box-sizing: border-box; }

    .orders-page-header { margin-bottom: 20px; }
    .orders-page-header h1 { font-size: 20px; font-weight: 650; margin: 0; }
    .crumb { font-size: 12.5px; color: var(--text-hint); margin-top: 3px; }
    .crumb a { color: var(--accent); text-decoration: none; }
    .crumb a:hover { text-decoration: underline; }
    .crumb span { margin: 0 5px; }

    .btn-primary-dash, .btn-secondary-dash {
        display: inline-flex; align-items: center; gap: 6px;
        border-radius: var(--radius-sm); padding: 9px 18px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        text-decoration: none !important; font-family: var(--font); border: none;
    }
    .btn-primary-dash  { background: var(--accent); color: #fff !important; }
    .btn-primary-dash:hover { background: #252f70; }
    .btn-secondary-dash { background: var(--surface); color: var(--text-primary) !important; border: 1px solid var(--border); font-weight: 500; }
    .btn-secondary-dash:hover { background: var(--bg); }

    .preview-toolbar {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;
        background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md);
        padding: 16px 20px; margin-bottom: 20px; box-shadow: var(--shadow-card);
        position: sticky; top: 0; z-index: 10;
    }
    .select-all-label { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; cursor: pointer; }
    .select-all-label input { width: 16px; height: 16px; }
    .selected-count { font-size: 12.5px; color: var(--text-hint); }
    .selected-count strong { color: var(--accent); }
    .size-badge { background: var(--accent-light); color: var(--accent); font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

    .preview-grid { display: grid; grid-template-columns: repeat({{ $cols }}, 1fr); gap: 18px; }
    @media(max-width: 768px) { .preview-grid { grid-template-columns: 1fr; } .preview-toolbar { flex-direction: column; align-items: stretch; } }

    .label-card-wrap { position: relative; }
    .label-checkbox {
        position: absolute; top: 12px; right: 12px; width: 20px; height: 20px; z-index: 2; cursor: pointer;
        accent-color: var(--accent);
    }

    .label-card { border: 1.5px solid var(--border); border-radius: 12px; overflow: hidden; background: #fff; box-shadow: var(--shadow-card); transition: opacity .15s; }
    .label-card.deselected { opacity: .38; }

    .lbl-header { background: var(--accent); color: #fff; padding: 14px 16px; }
    .brand-name { font-size: 13px; font-weight: 700; }
    .brand-tagline { font-size: 10px; color: rgba(255,255,255,.7); text-transform: uppercase; letter-spacing: .05em; margin-top: 2px; }
    .lbl-order-number { font-family: 'SF Mono','Fira Code',monospace; font-weight: 700; font-size: 13px; margin-top: 5px; }

    .lbl-body { padding: 16px; }
    .meta-heading { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--text-hint); border-bottom: 1px solid var(--border); padding-bottom: 5px; margin-bottom: 8px; }
    .ship-name { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
    .ship-address { font-size: 12.5px; color: var(--text-primary); line-height: 1.6; }
    .pincode-pill { display: inline-block; background: var(--accent-light); color: var(--accent); font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; margin-top: 8px; font-family: monospace; }
    .ship-phone { font-size: 12px; color: var(--text-secondary); margin-top: 6px; }

    .lbl-footer-row { margin-top: 10px; border-top: 1px solid var(--border); padding-top: 8px; display: flex; justify-content: space-between; }
    .footer-label { font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: var(--text-hint); }
    .footer-value { font-size: 12px; font-weight: 600; }
    </style>

    <div class="app-content content container-fluid">
        <div class="orders-page">

            <div class="orders-page-header">
                <h1>Label Preview</h1>
                <div class="crumb">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a> <span>›</span>
                    <a href="{{ route('admin.orders.index') }}">Manage Orders</a> <span>›</span>
                    <a href="{{ route('admin.orders.print-labels') }}">Print Label</a> <span>›</span>
                    Preview
                </div>
            </div>

            <form method="POST" action="{{ route('admin.orders.generate-labels') }}" target="_blank" id="previewForm">
                @csrf
                <input type="hidden" name="label_size" value="{{ $labelSize }}">

                <div class="preview-toolbar">
                    <label class="select-all-label">
                        <input type="checkbox" id="selectAllPreview" checked> Select All
                    </label>
                    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                        <span class="selected-count">
                            <strong id="selCount">{{ $orders->count() }}</strong> of {{ $orders->count() }} selected
                        </span>
                        <span class="size-badge">Size: {{ $labelSize }}</span>
                        <a href="{{ route('admin.orders.print-labels') }}" class="btn-secondary-dash">← Back</a>
                        <button type="submit" class="btn-primary-dash">🖨 Print</button>
                    </div>
                </div>

                <div class="preview-grid">
                    @foreach($orders as $order)
                        <div class="label-card-wrap">
                            <input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                                   class="label-checkbox preview-checkbox" checked>
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
                                    <div class="ship-phone">📞 {{ $order->customer_phone }}</div>
                                    <div class="lbl-footer-row">
                                        <div style="text-align:right">
                                            <div class="footer-label">Courier</div>
                                            <div class="footer-value">{{ optional($order->courier)->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>

        </div>
    </div>
</div>
@include('admin.footer')

@push('scripts')
<script>
    const selectAll  = document.getElementById('selectAllPreview');
    const checkboxes = document.querySelectorAll('.preview-checkbox');
    const countEl    = document.getElementById('selCount');

    function refreshCard(cb) {
        cb.closest('.label-card-wrap').querySelector('.label-card')
            .classList.toggle('deselected', !cb.checked);
    }

    function updateCount() {
        countEl.textContent = document.querySelectorAll('.preview-checkbox:checked').length;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => { cb.checked = this.checked; refreshCard(cb); });
        updateCount();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', function () {
        refreshCard(this);
        updateCount();
        selectAll.checked = document.querySelectorAll('.preview-checkbox:checked').length === checkboxes.length;
    }));

    document.getElementById('previewForm').addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.preview-checkbox:checked');
        if (checked.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'No Label Selected',
                text: 'Kripya kam se kam ek label select karein print karne ke liye.'
            });
        }
    });
</script>
@endpush