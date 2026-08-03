@extends('layouts.app')
@section('content')

<div class="cart-page-wrapper">

    <section class="cart-section" style="padding: 80px 0; background-color: #FAFAF8;">
        <div class="lp-container">
            <div class="cart-header text-center" style="margin-bottom: 48px;">
                <h1 class="cart-title"
                    style="font-family: 'Cinzel', serif; color: #1F5552; font-size: 36px; letter-spacing: 2px;">YOUR
                    CART</h1>
                <p class="cart-subtitle"
                    style="font-family: 'Outfit', sans-serif; color: #666; font-size: 16px; margin-top: 8px;">Review
                    your luxury selections.</p>
            </div>

            @if($cart && $cart->items->isNotEmpty())

            <div class="row g-5">

                {{-- Cart Items --}}
                <div class="col-lg-8">
                    <div class="cart-items-container">

                        {{-- Cart Item Header --}}
                        <div class="cart-item-header d-none d-md-flex">
                            <div class="col-product">Product</div>
                            <div class="col-qty">Quantity</div>
                            <div class="col-total">Total</div>
                        </div>

                        {{-- Cart Items Loop --}}
                        @foreach($cart->items as $item)
                        <div class="cart-item" id="cart-item-{{ $item->id }}">

                            <div class="cart-item-product">
                                <div class="cart-item-img">
                                    @if($item->product->defaultImage)
                                    <img src="{{ asset('storage/' . $item->product->defaultImage->image) }}"
                                        alt="{{ $item->product->name }}">
                                    @elseif($item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image) }}"
                                        alt="{{ $item->product->name }}">
                                    @else
                                    <img src="{{ asset('assets/images/placeholder.png') }}"
                                        alt="{{ $item->product->name }}">
                                    @endif
                                </div>
                                <div class="cart-item-details">
                                    <h3 class="cart-item-name">{{ $item->product->name }}</h3>
                                    @if($item->product->category)
                                    <p class="cart-item-type">{{ $item->product->category->name }}</p>
                                    @endif
                                    <p class="cart-item-price d-md-none">₹{{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>

                            <div class="cart-item-actions-wrapper">
                                <div class="cart-item-qty">
                                    <div class="quantity-selector">
                                        <button class="qty-btn minus" data-item-id="{{ $item->id }}" data-action="minus">
                                            @if($item->quantity <= 1) <i class="fa-solid fa-trash"></i>
                                                @else
                                                -
                                                @endif
                                        </button>
                                        <input type="number" class="qty-input" value="{{ $item->quantity }}"
                                            min="{{ $item->product->min_qty }}" readonly>
                                        <button class="qty-btn plus" data-item-id="{{ $item->id }}"
                                            data-action="plus">+</button>
                                    </div>
                                </div>

                                <div class="cart-item-total">
                                    <span id="item-total-{{ $item->id }}">₹{{ number_format($item->total, 2) }}</span>
                                    <button class="cart-item-remove-icon d-none d-md-block" data-item-id="{{ $item->id }}"
                                        aria-label="Remove item">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>

                                <button class="cart-item-remove d-md-none" data-item-id="{{ $item->id }}"
                                    aria-label="Remove item">Remove</button>
                            </div>

                        </div>
                        @endforeach

                    </div>
                </div>

                {{-- Cart Summary --}}
                <div class="col-lg-4">
                    <div class="cart-summary-card">
                        <h3 class="summary-title">ORDER SUMMARY</h3>

                        {{-- Coupon Input Row --}}
                        <!--<hr class="summary-divider">-->

                        <div class="mb-3">
                            <div class="d-flex gap-2">
                                <input type="text" id="coupon-code-input" class="form-control"
                                    placeholder="Discount code" value="{{ $cart->coupon_code ?? '' }}" {{
                                    $cart->coupon_code ? 'readonly' : '' }}
                                style="border: 1px solid rgba(35,75,70,0.2); border-radius: 8px; font-family: 'Outfit',
                                sans-serif; padding: 12px 16px; font-size: 14px;">
                                @if($cart->coupon_code)
                                <button id="remove-coupon-btn" class="lp-btn"
                                    style="white-space: nowrap;  border: 1px solid #1F5552;">Remove</button>
                                @else
                                <button id="apply-coupon-btn" class="lp-btn" style="white-space: nowrap;">Apply</button>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                <span id="coupon-msg"
                                    style="font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 500; display: none;"></span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#couponsModal"
                                    style="font-family: 'Outfit', sans-serif; font-size: 13px; color: #1F5552; ">
                                    View All Coupons
                                </a>
                            </div>
                        </div>

                        <hr class="summary-divider">

                        {{-- Totals --}}
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span id="summary-subtotal-2">₹{{ number_format($cart->subtotal, 2) }}</span>
                        </div>
                        <div class="summary-line">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        @if($cart->tax_amount > 0)
                        <div class="summary-line">
                            <span>
                                @if($cart->gst_type === 'cgst_sgst')
                                GST (CGST {{ $cart->cgst_rate }}% + SGST {{ $cart->sgst_rate }}%)
                                @else
                                GST (IGST {{ $cart->igst_rate }}%)
                                @endif
                            </span>
                            <span id="summary-tax">₹{{ number_format($cart->tax_amount, 2) }}</span>
                        </div>
                        @else
                        <div class="summary-line">
                            <span>Taxes</span>
                            <span>Calculated at checkout</span>
                        </div>
                        @endif
                        <div class="summary-line coupon-applied-row" id="summary-discount-row"
                            style="{{ $cart->discount > 0 ? 'display:flex;' : 'display:none;' }}">
                            <span>
                                Discount (<span id="discount-code-label">{{ $cart->coupon_code ?? '' }}</span>)
                            </span>
                            <span style="color: #2a7a56;">- ₹<span id="summary-discount">{{
                                    number_format($cart->discount, 2) }}</span></span>
                        </div>

                        <hr class="summary-divider">

                        <div class="summary-line total-line">
                            <span>Total</span>
                            <span id="summary-grand-total">₹{{ number_format($cart->grand_total, 2) }}</span>
                        </div>

                        <button class="lp-btn lp-btn-solid checkout-btn"
                            onclick="window.location.href='{{ route('checkout') }}'"
                            style="width: 100%; margin-top: 24px; padding: 14px; font-size: 16px;">
                            PROCEED TO CHECKOUT
                        </button>

                        <p class="secure-checkout"><i class="fa-solid fa-lock"></i> Secure Checkout</p>
                    </div>
                </div>

            </div>

            @else

            {{-- Empty Cart State --}}
            <div class="empty-cart text-center" style="padding: 80px 0;">
                <div style="font-size: 64px; color: #ccc; margin-bottom: 24px;">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h2 style="font-family: 'Cinzel', serif; color: #1F5552; font-size: 24px; margin-bottom: 12px;">Your
                    cart is empty</h2>
                <p style="font-family: 'Outfit', sans-serif; color: #888; margin-bottom: 32px;">Discover our collections
                    and add something you love.</p>
                <a href="{{ route('shop.all') }}" class="lp-btn lp-btn-solid"
                    style="padding: 14px 40px; font-size: 15px;">EXPLORE COLLECTIONS</a>
            </div>

            @endif

        </div>
    </section>

</div>

<style>
    .coupon-card--disabled {
        opacity: 0.6;
        background-color: #f5f5f5;
    }

    .coupon-ineligible-label {
        font-family: 'Outfit', sans-serif;
        font-size: 11px;
        color: #999;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
        white-space: nowrap;
    }
</style>

{{-- Coupons Modal --}}
<div class="modal fade" id="couponsModal" tabindex="-1" aria-labelledby="couponsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponsModalLabel"
                    style="font-family: 'Cinzel', serif; color: #1F5552; font-weight: 600; letter-spacing: 1px; font-size: 20px;">
                    AVAILABLE COUPONS</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="coupons-modal-body">
                <div class="text-center py-3" style="font-family: 'Outfit', sans-serif; color: #888;">
                    Loading coupons...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const CSRF = '{{ csrf_token() }}';

    // ─── Coupon UI reset (shared) ──────────────────────────────────────────────
    function handleCouponRevalidation(data) {
        if (data.coupon_removed) {
            const input = document.getElementById('coupon-code-input');
            const discountRow = document.getElementById('summary-discount-row');
            const removeBtn = document.getElementById('remove-coupon-btn');

            if (input) {
                input.value = '';
                input.removeAttribute('readonly');
            }

            if (discountRow) {
                discountRow.style.display = 'none';
            }

            if (removeBtn) {
                removeBtn.outerHTML = '<button id="apply-coupon-btn" class="lp-btn" style="white-space: nowrap;">Apply</button>';
                const newApplyBtn = document.getElementById('apply-coupon-btn');
                newApplyBtn.addEventListener('click', function () {
                    applyCouponCode(document.getElementById('coupon-code-input').value.trim().toUpperCase());
                });
            }

            if (data.coupon_message) {
                showToast(data.coupon_message, 'error');
            }
        }

        if (typeof data.discount !== 'undefined') {
            const discountEl = document.getElementById('summary-discount');
            if (discountEl) discountEl.textContent = formatNum(data.discount);
        }
    }

    // ─── Quantity Update ──────────────────────────────────────────────────────
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const itemId = this.dataset.itemId;
            const action = this.dataset.action;

            const row = document.getElementById('cart-item-' + itemId);
            const qty = parseInt(row.querySelector('.qty-input').value);

            // If trash icon is showing, remove item
            if (action === 'minus' && qty <= 1) {
                fetch(`{{ route('cart.remove', ['id' => '__ID__']) }}`.replace('__ID__', itemId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status) {
                            row.remove();
                            fireTrackingEvents(data.tracking_events);
                            updateCartCount(data.cart_count ?? null);
                            handleCouponRevalidation(data);

                            if (data.cart_count === 0) {
                                location.reload();
                            } else {
                                document.getElementById('summary-grand-total').textContent = '₹' + formatNum(data.cart_total);
                            }
                        }
                    });

                return;
            }

            fetch('{{ route("cart.update-quantity") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify({ item_id: itemId, action: action }),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        const row = document.getElementById('cart-item-' + itemId);
                        row.querySelector('.qty-input').value = data.quantity;
                        const minusBtn = row.querySelector('.qty-btn.minus');

                        if (data.quantity <= 1) {
                            minusBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        } else {
                            minusBtn.innerHTML = '-';
                        }
                        document.getElementById('item-total-' + itemId).textContent = '₹' + formatNum(data.item_total);
                        document.getElementById('summary-grand-total').textContent = '₹' + formatNum(data.cart_total);
                        updateCartCount(data.cart_count ?? null);
                        handleCouponRevalidation(data);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(() => showToast('Something went wrong.', 'error'));
        });
    });

    // ─── Remove Item ─────────────────────────────────────────────────────────
    document.querySelectorAll('.cart-item-remove, .cart-item-remove-icon').forEach(btn => {
        btn.addEventListener('click', function () {
            const itemId = this.dataset.itemId;

            fetch(`{{ route('cart.remove', ['id' => '__ID__']) }}`.replace('__ID__', itemId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        fireTrackingEvents(data.tracking_events);
                        document.getElementById('cart-item-' + itemId)?.remove();
                        document.getElementById('summary-grand-total').textContent = '₹' + formatNum(data.cart_total);
                        updateCartCount(data.cart_count ?? null);
                        handleCouponRevalidation(data);

                        if (data.cart_count === 0) {
                            location.reload();
                        }
                    }
                })
                .catch(() => showToast('Something went wrong.', 'error'));
        });
    });

    // ─── Apply Coupon ─────────────────────────────────────────────────────────
    function applyCouponCode(code) {
        const msg = document.getElementById('coupon-msg');
        if (!code) return;

        fetch('{{ route("cart.apply-coupon") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ coupon_code: code }),
        })
            .then(r => r.json())
            .then(data => {
                msg.style.display = 'block';
                msg.style.color = data.status ? '#2e7d32' : '#c62828';
                msg.textContent = data.message;
                if (data.status) location.reload();
            })
            .catch(() => showToast('Something went wrong.', 'error'));
    }

    const applyBtn = document.getElementById('apply-coupon-btn');
    if (applyBtn) {
        applyBtn.addEventListener('click', function () {
            const code = document.getElementById('coupon-code-input').value.trim().toUpperCase();
            applyCouponCode(code);
        });
    }

    const couponInput = document.getElementById('coupon-code-input');
    if (couponInput) {
        couponInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCouponCode(this.value.trim().toUpperCase());
            }
        });
    }

    // ─── Remove Coupon ────────────────────────────────────────────────────────
    const removeCouponBtn = document.getElementById('remove-coupon-btn');
    if (removeCouponBtn) {
        removeCouponBtn.addEventListener('click', function () {
            fetch('{{ route("cart.remove-coupon") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
            })
                .then(r => r.json())
                .then(data => { if (data.status) location.reload(); })
                .catch(() => showToast('Something went wrong.', 'error'));
        });
    }

    // ─── Coupons Modal: Load available coupons via API ─────────────────────
    const couponsModalEl = document.getElementById('couponsModal');
    if (couponsModalEl) {
        couponsModalEl.addEventListener('show.bs.modal', function () {
            const body = document.getElementById('coupons-modal-body');

            fetch('{{ route("cart.available-coupons") }}', {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.coupons || data.coupons.length === 0) {
                        body.innerHTML = `<p style="font-family:'Outfit',sans-serif;color:#888;text-align:center;padding:16px 0;">No coupons available right now.</p>`;
                        return;
                    }
                    body.innerHTML = data.coupons.map((c, i) => `
                            <div class="coupon-card ${i < data.coupons.length - 1 ? 'mb-3' : ''} ${!c.eligible ? 'coupon-card--disabled' : ''}">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="coupon-badge">${c.code}</span>
                                    ${c.eligible
                            ? `<button class="btn-use-coupon" data-code="${c.code}">USE COUPON</button>`
                            : `<span class="coupon-ineligible-label">Min. ₹${Number(c.min_amount).toLocaleString('en-IN')} required</span>`
                        }
                                </div>
                                <div style="font-family:'Outfit',sans-serif;font-size:13px;color:${c.eligible ? '#666' : '#aaa'};line-height:1.4;">
                                    ${c.description}
                                </div>
                            </div>
                        `).join('');

                    // Wire up USE COUPON buttons
                    body.querySelectorAll('.btn-use-coupon').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const code = btn.dataset.code;
                            document.getElementById('coupon-code-input').value = code;
                            bootstrap.Modal.getInstance(couponsModalEl)?.hide();
                            applyCouponCode(code);
                        });
                    });
                })
                .catch(() => {
                    body.innerHTML = `<p style="font-family:'Outfit',sans-serif;color:#c62828;text-align:center;">Could not load coupons.</p>`;
                });
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────
    function formatNum(val) {
        return parseFloat(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateCartCount(count) {
        if (count === null) return;
        document.querySelectorAll('.cart-count, [data-cart-count]').forEach(el => el.textContent = count);
    }

    function showToast(message, type = 'info') {
        // Wire up to your existing toast/notification system
        alert(message);
    }
</script>


@endsection