@extends('layouts.app')
@section('content')

    <div class="dashboard-page-wrapper">
        <section class="dashboard-section">
            <div class="lp-container">
                <div class="dashboard-layout">

                    @include('user._sidebar')

                    <main class="dashboard-main">
                        <button class="dashboard-sidebar-toggle">
                            <i class="fa-solid fa-bars"></i> Menu
                        </button>

                        <div class="orders-header dashboard-content-header">
                            <a href="{{ route('user.orders') }}" class="back-link">
                                <i class="fa-solid fa-arrow-left"></i> Back to Orders
                            </a>
                            <h1 class="orders-title">ORDER {{ $order->order_number }}</h1>
                            <p class="orders-subtitle">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                        </div>

                        <div class="order-details-wrapper">

                            {{-- Progress Tracker --}}
                            @php
                                $steps = [
                                    ['label' => 'Order Placed', 'icon' => 'fa-solid fa-bag-shopping', 'status' => 'pending'],
                                    ['label' => 'Processing', 'icon' => 'fa-solid fa-box-open', 'status' => 'processing'],
                                    ['label' => 'Shipped', 'icon' => 'fa-solid fa-truck-fast', 'status' => 'shipped'],
                                    ['label' => 'Delivered', 'icon' => 'fa-solid fa-house', 'status' => 'delivered'],
                                ];

                                $stepOrder = ['pending' => 0, 'processing' => 1, 'confirmed' => 1, 'shipped' => 2, 'delivered' => 3];
                                $currentStep = $stepOrder[strtolower($order->status)] ?? 0;

                                // Map status history to step index for dates
                                $historyMap = $order->statusHistory
                                    ->keyBy(fn($h) => strtolower($h->status))
                                    ->map(fn($h) => $h->created_at->format('M d'));
                            @endphp

                            @if (!in_array($order->status, ['cancelled', 'failed']))
                                <div class="order-progress-card">
                                    <div class="progress-track">
                                        @foreach ($steps as $i => $step)
                                            @php
                                                $isCompleted = $i < $currentStep;
                                                $isActive = $i === $currentStep;
                                                $date = $historyMap[$step['status']] ?? null;
                                            @endphp
                                            <div class="progress-step {{ $isCompleted || $isActive ? 'active' : '' }}">
                                                <div class="step-icon">
                                                    <i class="{{ $isCompleted ? 'fa-solid fa-check' : $step['icon'] }}"></i>
                                                </div>
                                                <div class="step-label">{{ $step['label'] }}</div>
                                                <div class="step-date">
                                                    @if ($date)
                                                        {{ $date }}
                                                    @elseif (!$isCompleted && !$isActive)
                                                        —
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="order-progress-card" style="text-align:center; padding: 1.5rem;">
                                    <span class="status-badge status-{{ strtolower($order->status) }}"
                                        style="font-size:14px; padding: 6px 18px;">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if ($order->cancellation_reason)
                                        <p style="margin-top: 10px; color: #888; font-size:13px;">
                                            Reason: {{ $order->cancellation_reason }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <div class="order-details-grid">

                                {{-- Left Column --}}
                                <div class="order-details-main">

                                    {{-- Items --}}
                                    <div class="details-card">
                                        <h3 class="details-card-title">Items in your order</h3>
                                        <div class="order-items-list">
                                            @foreach ($order->items as $item)
                                                <div class="order-item-detailed">
                                                    <img src="{{ $item->product?->display_image ?? asset('assets/images/placeholder.png') }}"
                                                        alt="{{ $item->product_name }}">
                                                    <div class="item-info">
                                                        <h4>{{ $item->product_name }}</h4>
                                                        <p>{{  $item->product?->weight }}ml</p>
                                                        <p>Qty: {{ $item->quantity }}</p>
                                                        <p style="font-size:12px;color:#8c9196;">HSN:
                                                            {{ $item->product?->hsn_code ?? '—' }}</p>
                                                    </div>
                                                    <div class="item-price">
                                                        ₹{{ number_format($item->price * $item->quantity, 2) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Addresses --}}
                                    <div class="details-card address-card-split">
                                        <div class="address-col">
                                            <h3 class="details-card-title">Shipping Address</h3>
                                            <div class="address-display">
                                                <p class="address-name">
                                                    {{ $order->shipping_name ?? $order->customer->name }}
                                                </p>
                                                <p>{{ $order->address_line_1 }}</p>
                                                @if ($order->address_line_2)
                                                    <p>{{ $order->address_line_2 }}</p>
                                                @endif
                                                <p>{{ $order->city?->name }}, {{ $order->state?->name }}
                                                    {{ $order->pincode }}
                                                </p>
                                                <p>India</p>
                                                @if ($order->phone)
                                                    <p>Phone: {{ $order->phone }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="address-col">
                                            <h3 class="details-card-title">Payment Info</h3>
                                            <div class="address-display">
                                                <p class="address-name">
                                                    {{ strtolower($order->payment_method ?? 'cod') === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}
                                                </p>
                                                <p>Status:
                                                    <span
                                                        class="status-badge status-{{ strtolower($order->payment_status ?? 'pending') }}"
                                                        style="font-size:11px;">
                                                        {{ ucfirst($order->payment_status ?? 'Pending') }}
                                                    </span>
                                                </p>
                                                @if ($order->tracking_number)
                                                    <p style="margin-top:8px;">
                                                        <strong>Tracking:</strong> {{ $order->tracking_number }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{-- Right Column: Summary --}}
                                <div class="order-details-sidebar">
                                    <div class="details-card summary-card">
                                        <h3 class="details-card-title">Order Summary</h3>

                                        <div class="summary-row">
                                            <span>Subtotal ({{ $order->items->count() }}
                                                {{ Str::plural('item', $order->items->count()) }})</span>
                                            <span>₹{{ number_format($order->subtotal ?? $order->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                                        </div>
                                        @if($order->discount > 0)
                                            <div class="summary-row" style="color: #2E7D32;">
                                                <span>
                                                    Discount
                                                    @if($order->coupon_code)
                                                        <span style="font-size:11px;">({{ $order->coupon_code }})</span>
                                                    @endif
                                                </span>
                                                <span>− ₹{{ number_format($order->discount, 2) }}</span>
                                            </div>
                                        @endif
                                        @if($order->tax_amount > 0)
                                            <div class="summary-row">
                                                <span>Tax @if($order->gst_type === 'igst')
                                                    <span style="font-size:11px;">(IGST {{ $order->igst_rate }}%)</span>
                                                @else
                                                        <span style="font-size:11px;">(CGST {{ $order->cgst_rate }}% + SGST
                                                            {{ $order->sgst_rate }}%)</span>
                                                    @endif</span>
                                                <span>{{ $order->tax_amount > 0 ? '₹' . number_format($order->tax_amount, 2) : 'Inclusive' }}</span>
                                            </div>

                                        @endif
                                        <div class="summary-divider"></div>

                                        <div class="summary-row total-row">
                                            <span>Total</span>
                                            <span>₹{{ number_format($order->grand_total, 2) }}</span>
                                        </div>

                                        <div class="summary-actions">
                                            @if ($order->invoice)
                                                <a href="{{ route('user.orders.invoice', $order->id) }}"
                                                    class="lp-btn lp-btn-solid w-100 mb-2" target="_blank">
                                                    Download Invoice
                                                </a>
                                            @endif

                                            <a href="{{ route('user.orders.reorder', $order->id) }}"
                                                class="lp-btn lp-btn-outline w-100">
                                                Buy Again
                                            </a>

                                            {{-- Return within 7 days --}}
                                            <!-- @if ($order->status === 'delivered' && $order->created_at->diffInDays(now()) <= 7)
                                                    @php
                                                        $existingReturn = $order->returns()
                                                            ->whereIn('status', ['pending', 'approved', 'completed'])
                                                            ->first();
                                                    @endphp
                                                    @if ($existingReturn)
                                                        <p style="text-align:center; font-size:13px; color:#888; margin-top:10px;">
                                                            Return request {{ $existingReturn->status }}
                                                        </p>
                                                    @else
                                                        <button class="lp-btn lp-btn-outline w-100 mt-2 btn-open-return"
                                                                data-order-id="{{ $order->id }}"
                                                                data-order-number="{{ $order->order_number }}"
                                                                data-order-items="{{ $order->items->pluck('product_name', 'id')->toJson() }}">
                                                            Return / Exchange
                                                        </button>
                                                    @endif
                                                @endif -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </main>

                </div>
            </div>
        </section>

        {{-- Return Modal --}}
        <div class="lp-modal-overlay" id="return-modal" style="display:none;">
            <div class="lp-modal" style="max-width:480px;">
                <div class="lp-modal-header">
                    <h3>Return / Exchange</h3>
                    <button class="lp-modal-close" id="btn-close-return-modal">&times;</button>
                </div>
                <p style="color:#888; font-size:13px; margin-bottom:1rem;" id="return-order-meta"></p>

                <form action="{{ route('user.orders.return') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" id="return-order-id">

                    <div class="rtn-field-group">
                        <label class="rtn-label">Select Item <span class="rtn-req">*</span></label>
                        <select class="rtn-select" name="order_item_id" id="return-item-select" required>
                            <option value="" disabled selected>Select an item</option>
                        </select>
                    </div>

                    <div class="rtn-field-group">
                        <label class="rtn-label">Request Type <span class="rtn-req">*</span></label>
                        <div class="rtn-type-row">
                            <label class="rtn-type-opt">
                                <input type="radio" name="type" value="return" checked>
                                <span class="rtn-type-btn"><i class="fa-solid fa-arrow-rotate-left"></i> Return</span>
                            </label>
                            <label class="rtn-type-opt">
                                <input type="radio" name="type" value="exchange">
                                <span class="rtn-type-btn"><i class="fa-solid fa-arrow-right-arrow-left"></i>
                                    Exchange</span>
                            </label>
                        </div>
                    </div>

                    <div class="rtn-field-group">
                        <label class="rtn-label">Reason <span class="rtn-req">*</span></label>
                        <select class="rtn-select" name="return_reason_id" required>
                            <option value="" disabled selected>Select a reason</option>
                            @foreach ($returnReasons as $reason)
                                <option value="{{ $reason->id }}">{{ $reason->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="rtn-field-group">
                        <label class="rtn-label">Additional Details</label>
                        <textarea class="rtn-textarea" name="details" rows="2" placeholder="Describe the issue…"></textarea>
                    </div>

                    <div class="rtn-divider"><span>Refund Information</span></div>

                    <div class="rtn-field-group">
                        <label class="rtn-label">Refund Via <span class="rtn-req">*</span></label>
                        <select class="rtn-select" name="refund_method" id="refund-method" required
                            onchange="switchRefundMethod(this.value)">
                            <option value="" disabled selected>Select method</option>
                            <option value="upi">UPI ID</option>
                            <option value="qr">QR Code</option>
                            <option value="bank">Bank Details</option>
                        </select>
                    </div>

                    <div id="rtnPanelUpi" style="display:none;">
                        <div class="rtn-field-group">
                            <label class="rtn-label">UPI ID <span class="rtn-req">*</span></label>
                            <div class="rtn-input-icon-wrap">
                                <i class="fa-solid fa-building-columns rtn-input-icon"></i>
                                <input type="text" class="rtn-input rtn-input-icon-pad" name="upi_id" id="upi-id-field"
                                    placeholder="yourname@upi">
                            </div>
                        </div>
                    </div>

                    <div id="rtnPanelQr" style="display:none;">
                        <div class="rtn-field-group">
                            <label class="rtn-label">Upload QR Code <span class="rtn-req">*</span></label>
                            <div class="rtn-upload-area" onclick="document.getElementById('qr-file').click()">
                                <input type="file" name="qr_image" id="qr-file" accept="image/*" style="display:none"
                                    onchange="previewQr(this)">
                                <div id="qr-placeholder">
                                    <i class="fa-solid fa-qrcode" style="font-size:26px; color:#8c9196;"></i>
                                    <p style="margin:6px 0 0; font-size:13px; color:#6d7175;">Click to upload your UPI QR
                                    </p>
                                </div>
                                <div id="qr-preview" style="display:none; text-align:center;">
                                    <img id="qr-preview-img" src="" style="max-width:130px; border-radius:8px;">
                                    <div style="margin-top:6px;">
                                        <button type="button" onclick="clearQr(event)"
                                            style="font-size:12px; color:#b22222; background:none; border:none; cursor:pointer;">
                                            <i class="fa-solid fa-times"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="rtnPanelBank" style="display:none;">
                        <div class="rtn-field-group">
                            <label class="rtn-label">Bank Name <span class="rtn-req">*</span></label>
                            <input type="text" class="rtn-input" name="bank_name" placeholder="e.g. State Bank of India">
                        </div>
                        <div class="rtn-field-group">
                            <label class="rtn-label">Account Holder Name <span class="rtn-req">*</span></label>
                            <input type="text" class="rtn-input" name="account_name" placeholder="Name as on bank account">
                        </div>
                        <div class="rtn-2col">
                            <div class="rtn-field-group">
                                <label class="rtn-label">Account Number <span class="rtn-req">*</span></label>
                                <input type="text" class="rtn-input" name="account_number" inputmode="numeric">
                            </div>
                            <div class="rtn-field-group">
                                <label class="rtn-label">IFSC Code <span class="rtn-req">*</span></label>
                                <input type="text" class="rtn-input" name="ifsc_code" style="text-transform:uppercase"
                                    oninput="this.value=this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="rtn-2col">
                            <div class="rtn-field-group">
                                <label class="rtn-label">Branch</label>
                                <input type="text" class="rtn-input" name="bank_branch">
                            </div>
                            <div class="rtn-field-group">
                                <label class="rtn-label">Account Type <span class="rtn-req">*</span></label>
                                <select class="rtn-select" name="account_type">
                                    <option value="" disabled selected>Select</option>
                                    <option value="savings">Savings</option>
                                    <option value="current">Current</option>
                                    <option value="salary">Salary</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="rtn-submit-btn">
                        <i class="fa-solid fa-paper-plane"></i> Submit Return Request
                    </button>
                </form>
            </div>
        </div>

        <script>
            const returnModal = document.getElementById('return-modal');

            function openModal(el) { el.style.display = 'flex'; }
            function closeModal(el) { el.style.display = 'none'; }

            document.getElementById('btn-close-return-modal').addEventListener('click', () => closeModal(returnModal));
            returnModal.addEventListener('click', e => { if (e.target === returnModal) closeModal(returnModal); });

            document.querySelectorAll('.btn-open-return').forEach(btn => {
                btn.addEventListener('click', () => {
                    const items = JSON.parse(btn.dataset.orderItems || '{}');

                    document.getElementById('return-order-id').value = btn.dataset.orderId;
                    document.getElementById('return-order-meta').textContent =
                        `Requesting a return for Order #${btn.dataset.orderNumber}`;

                    const select = document.getElementById('return-item-select');
                    select.innerHTML = '<option value="" disabled selected>Select an item</option>';
                    Object.entries(items).forEach(([id, name]) => {
                        const opt = document.createElement('option');
                        opt.value = id;
                        opt.textContent = name;
                        select.appendChild(opt);
                    });

                    document.querySelectorAll('.rtn-type-opt input[type=radio]').forEach(r => {
                        r.checked = r.value === 'return';
                    });

                    document.getElementById('refund-method').value = '';
                    switchRefundMethod('');
                    clearQr();

                    openModal(returnModal);
                });
            });

            function switchRefundMethod(val) {
                ['Upi', 'Qr', 'Bank'].forEach(k => {
                    const panel = document.getElementById('rtnPanel' + k);
                    if (panel) panel.style.display = val === k.toLowerCase() ? 'block' : 'none';
                });
                const upiField = document.getElementById('upi-id-field');
                if (upiField) upiField.required = val === 'upi';
                const qrFile = document.getElementById('qr-file');
                if (qrFile) qrFile.required = val === 'qr';
            }

            function previewQr(input) {
                if (!input.files?.[0]) return;
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('qr-preview-img').src = e.target.result;
                    document.getElementById('qr-preview').style.display = 'block';
                    document.getElementById('qr-placeholder').style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }

            function clearQr(e) {
                if (e) e.stopPropagation();
                const f = document.getElementById('qr-file');
                if (f) f.value = '';
                const preview = document.getElementById('qr-preview');
                const placeholder = document.getElementById('qr-placeholder');
                if (preview) preview.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            }
        </script>


    </div>

@endsection