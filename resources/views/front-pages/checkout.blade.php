@extends('layouts.app')
@section('content')

    <div class="checkout-page-wrapper">

        <section class="checkout-section" style="padding: 80px 0; background-color: #FAFAF8;">
            <div class="lp-container">
                <div class="checkout-header text-center" style="margin-bottom: 48px;">
                    <h1 class="checkout-title"
                        style="font-family: 'Cinzel', serif; color: #1F5552; font-size: 36px; letter-spacing: 2px;">
                        CHECKOUT</h1>
                    <p class="checkout-subtitle"
                        style="font-family: 'Outfit', sans-serif; color: #666; font-size: 16px; margin-top: 8px;">
                        Complete your purchase securely.</p>
                </div>

                <div class="row g-5">
                    <!-- Left Column: Forms -->
                    <div class="col-lg-7">
                        <div class="checkout-form-container">
                            <!-- Contact Info -->
                            <div class="checkout-section-block">
                                <h2 class="checkout-section-title">Contact Information</h2>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email Address"
                                        value="{{ auth('customer')->user()?->email ?? '' }}">
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="newsOffers" checked>
                                    <label class="form-check-label" for="newsOffers">
                                        Email me with news and exclusive offers
                                    </label>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="checkout-section-block mt-5">
                                <h2 class="checkout-section-title" style="margin-bottom: 24px;">Shipping Address</h2>

                                <div class="address-cards-row mb-4">
                                    <div class="row g-3">

                                        {{-- Add New Address --}}
                                        <div class="col-md-4">
                                            <div class="address-card add-new-card" id="card-new-address">
                                                <div class="add-new-content">
                                                    <span class="plus-icon">+</span>
                                                    <span class="add-text">Add New Address</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Saved Addresses --}}
                                        @foreach($addresses as $address)
                                            <div class="col-md-4">
                                                <div class="address-card existing-address-card {{ $address->is_default ? 'active' : '' }}"
                                                    data-address-id="{{ $address->id }}">

                                                    @if($address->is_default)
                                                        <span class="default-badge">DEFAULT SHIPPING</span>
                                                    @endif

                                                    <div>
                                                        <h3 class="address-name">
                                                            {{ $address->name }}
                                                        </h3>

                                                        <p class="address-details-text">
                                                            {{ $address->address_line_1 }}

                                                            @if($address->address_line_2)
                                                                <br>{{ $address->address_line_2 }}
                                                            @endif

                                                            <br>{{ $address->city?->name }},
                                                            {{ $address->state?->name }}
                                                            {{ $address->pincode }}

                                                            <br>India
                                                        </p>

                                                        <p class="address-phone-text">
                                                            Phone: {{ $address->phone }}
                                                        </p>
                                                    </div>

                                                    <div>
                                                        <hr class="card-divider">

                                                        <div class="card-actions">

                                                            <a href="#" class="action-edit" data-id="{{ $address->id }}"
                                                                data-name="{{ $address->name }}"
                                                                data-email="{{ $address->email }}"
                                                                data-phone="{{ $address->phone }}"
                                                                data-address1="{{ $address->address_line_1 }}"
                                                                data-address2="{{ $address->address_line_2 }}"
                                                                data-state="{{ $address->state_id }}"
                                                                data-city="{{ $address->city_id }}"
                                                                data-pincode="{{ $address->pincode }}"
                                                                data-type="{{ $address->address_type }}">
                                                                Edit
                                                            </a>

                                                            @unless($address->is_default)
                                                                <a href="#" class="action-default-set"
                                                                    data-address-id="{{ $address->id }}">
                                                                    Set as Default
                                                                </a>
                                                            @endunless

                                                            <a href="#" class="action-delete"
                                                                data-address-id="{{ $address->id }}">
                                                                Delete
                                                            </a>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                                <form id="new-address-form">
                                    @csrf

                                    <div class="row g-3" id="shipping-form-fields" style="display:none;">
                                        <input type="hidden" name="address_id" id="address_id">
                                        <div class="col-md-6">
                                            <input type="text" name="name" class="form-control" placeholder="Full Name"
                                                value="{{ auth('customer')->user()?->name ?? '' }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Email Address"
                                                value="{{ auth('customer')->user()?->email ?? '' }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number"
                                                value="{{ auth('customer')->user()?->mobile ?? '' }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <select class="form-select" name="address_type">
                                                <option value="home">Home</option>
                                                <option value="office">Office</option>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <input type="text" class="form-control" name="address_line_1"
                                                placeholder="Address Line 1" required>
                                        </div>

                                        <div class="col-12">
                                            <input type="text" class="form-control" name="address_line_2"
                                                placeholder="Address Line 2">
                                        </div>

                                        <div class="col-md-4">
                                            <select class="form-select" name="state_id" id="state_id" required>

                                                <option value="">Select State</option>

                                                @foreach($states as $state)
                                                    <option value="{{ $state->id }}">
                                                        {{ $state->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <select class="form-select" name="city_id" id="city_id" required>
                                                <option value="">Select City</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" name="pincode" class="form-control" placeholder="Pincode"
                                                required>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="lp-btn lp-btn-solid">
                                                Save Address
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="col-lg-5">
                        <div class="checkout-summary-card">

                            <!-- Items -->
                            @if($cart && $cart->items && $cart->items->count())


                            @foreach($cart->items as $item)
                                <div class="checkout-item">

                                    <div class="checkout-item-img">
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

                                        <span class="checkout-item-badge">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>

                                    <div class="checkout-item-details">
                                        <h3 class="checkout-item-name">
                                            {{ $item->product->name }}
                                        </h3>

                                        @if($item->product->category)
                                            <p class="checkout-item-type">
                                                {{ $item->product->category->name }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="checkout-item-price">
                                        ₹{{ number_format($item->total, 2) }}
                                    </div>

                                </div>
                            @endforeach

                            <hr class="checkout-divider">

                            <!-- Discount Code -->
                            <div class="mb-4">
                                <div class="d-flex gap-2">
                                    <input type="text" id="coupon-code-input" class="form-control"
                                        placeholder="Discount code" value="{{ $cart->coupon_code ?? '' }}" {{ $cart->coupon_code ? 'readonly' : '' }}
                                        style="border: 1px solid rgba(35,75,70,0.2); border-radius: 8px; font-family: 'Outfit', sans-serif; padding: 12px 16px; font-size: 14px;">
                                    @if($cart->coupon_code)
                                        <button id="remove-coupon-btn" class="lp-btn"
                                            style="white-space: nowrap; color:black; border: 1px solid #1F5552;">Remove</button>
                                    @else
                                        <button id="apply-coupon-btn" class="lp-btn">Apply</button>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                    <span id="coupon-message"
                                        style="font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 500; display: none;"></span>
                                    <a href="#" id="view-coupons-link" data-bs-toggle="modal"
                                        data-bs-target="#couponsModal">
                                        View All Coupons
                                    </a>
                                </div>
                            </div>
                            <hr class="checkout-divider">

                            <!-- Totals -->
                            <div class="summary-line">
                                <span>Subtotal</span>
                                <span id="subtotal-val">₹{{ number_format($cart->subtotal, 2) }}</span>
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
                                    <span id="taxes-val">₹{{ number_format($cart->tax_amount, 2) }}</span>
                                </div>
                            @else
                                <div class="summary-line">
                                    <span>Taxes</span>
                                    <span>Calculated When Choose Address</span>
                                </div>
                            @endif

                            <div class="summary-line coupon-applied-row" id="discount-row"
                                style="{{ $cart->discount > 0 ? 'display:flex;' : 'display:none;' }}">
                                <span>
                                    Discount (<span id="discount-code-label">{{ $cart->coupon_code ?? '' }}</span>)
                                </span>
                                <span>- ₹<span id="discount-val">{{ number_format($cart->discount, 2) }}</span></span>
                            </div>
                            <hr class="checkout-divider">
                            <div class="summary-line total-line" style="font-size: 20px; color: #1F5552;">
                                <span>Total</span>
                                <span class="fw-bold" id="total-val">₹{{ number_format($cart->grand_total, 2) }}</span>
                            </div>



                            <button class="lp-btn lp-btn-solid w-100 mt-4" style="padding: 16px; font-size: 16px;"
                                id="place-order-btn">
                                COMPLETE ORDER
                            </button>
                            <p class="secure-checkout mt-3 text-center"><i class="fa-solid fa-lock"></i> Secure
                                Encrypted Checkout</p>
                                
                                
@else

    <div class="text-center py-4">
        <h5>Your cart is empty</h5>
    </div>

@endif
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Coupons Modal -->
        <div class="modal fade" id="couponsModal" tabindex="-1" aria-labelledby="couponsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="couponsModalLabel"
                            style="font-family: 'Cinzel', serif; color: #1F5552; font-weight: 600; letter-spacing: 1px; font-size: 20px;">
                            AVAILABLE COUPONS</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="coupons-modal-body">
                        <div class="text-center py-3" style="font-family: 'Outfit', sans-serif; color: #888;">
                            Loading coupons...
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


    <div id="payment-overlay" style="display:none;">
        <div class="payment-loader">
            <div class="spinner-border text-light" role="status"></div>
            <p>Please wait, processing your payment...</p>
        </div>
    </div>

    <style>
        #payment-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            backdrop-filter: blur(5px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-loader {
            text-align: center;
            color: #fff;
            font-size: 18px;
        }

        .payment-loader p {
            margin-top: 15px;
            margin-bottom: 0;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const CSRF = '{{ csrf_token() }}';

        // ─── Apply Coupon ─────────────────────────────────────────────────────────
        function applyCouponCode(code) {
            const msg = document.getElementById('coupon-message');
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

        function showToast(message, type = 'info') {
            // Wire up to your existing toast/notification system
            alert(message);
        }

        $('#state_id').on('change', function () {

            let stateId = $(this).val();

            $('#city_id').html('<option>Loading...</option>');

            $.get('{{ route("get-cities") }}', {
                state_id: stateId
            }, function (response) {

                let html = '<option value="">Select City</option>';

                response.forEach(city => {
                    html += `<option value="${city.id}">
                                                                            ${city.name}
                                                                        </option>`;
                });

                $('#city_id').html(html);

            });

        });


        $('#new-address-form').submit(function (e) {

            e.preventDefault();

            $.ajax({
                url: '{{ route("address.store") }}',
                type: 'POST',
                data: $(this).serialize(),

                success: function (response) {

                    if (response.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Address saved successfully'
                        }).then(() => {
                            location.reload();
                        });

                    }

                }

            });

        });


        $(document).on('click', '.action-default-set', function (e) {

            e.preventDefault();

            let addressId = $(this).data('address-id');

            $.ajax({
                url: '{{ route("checkout.change-default-address") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    address_id: addressId
                },

                success: function (response) {

                    if (response.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Default address updated'
                        }).then(() => {
                            location.reload();
                        });

                    }

                }

            });

        });

        $(document).on('click', '.action-edit', function (e) {

            e.preventDefault();

            let stateId = $(this).data('state');
            let cityId = $(this).data('city');

            $('#shipping-form-fields').show();

            $('#address_id').val($(this).data('id'));

            $('[name=name]').val($(this).data('name'));
            $('[name=email]').val($(this).data('email'));
            $('[name=phone]').val($(this).data('phone'));
            $('[name=address_line_1]').val($(this).data('address1'));
            $('[name=address_line_2]').val($(this).data('address2'));
            $('[name=pincode]').val($(this).data('pincode'));
            $('[name=address_type]').val($(this).data('type'));

            $('#state_id').val(stateId);

            $.get('{{ route("get-cities") }}', {
                state_id: stateId
            }, function (response) {

                let html = '<option value="">Select City</option>';

                response.forEach(city => {

                    html += `<option value="${city.id}">
                                                        ${city.name}
                                                     </option>`;

                });

                $('#city_id').html(html);

                // preselect city after cities are loaded
                $('#city_id').val(cityId);

            });

        });

        $('#place-order-btn').click(function () {

            let addressId = $('.existing-address-card.active').data('address-id');

            if (!addressId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Address Required',
                    text: 'Please add or select a shipping address before placing the order.'
                });

                return;
            }


            $(this)
                .prop('disabled', true)
                .text('Processing...');

            $.ajax({
                url: '{{ route("checkout.place-order") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_method: 'razorpay',
                    address_id: addressId
                },
                success: function (response) {

                    if (!response.success) {

                        $('#place-order-btn')
                            .prop('disabled', false)
                            .text('COMPLETE ORDER');

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops',
                            text: response.message
                        });

                        return;
                    }

                    const options = {

                        key: response.key,

                        amount: response.amount,

                        currency: 'INR',

                        name: 'La Pavone',

                        description: 'Order Payment',

                        order_id: response.razorpay_order_id,

                        modal: {
                            ondismiss: function () {
                                $('#place-order-btn')
                                    .prop('disabled', false)
                                    .text('COMPLETE ORDER');
                            }
                        },
                        prefill: {

                            name: response.customer_name,

                            email: response.customer_email,

                            contact: response.customer_phone

                        },

                        handler: function (paymentResponse) {

                            document.getElementById('payment-overlay').style.display = 'flex';


                            $.ajax({

                                url: '{{ route("checkout.razorpay.success") }}',

                                type: 'POST',

                                data: {

                                    _token: '{{ csrf_token() }}',

                                    order_id: response.order_id,

                                    razorpay_payment_id:
                                        paymentResponse.razorpay_payment_id,

                                    razorpay_order_id:
                                        paymentResponse.razorpay_order_id,

                                    razorpay_signature:
                                        paymentResponse.razorpay_signature
                                },

                                success: function (res) {

                                    if (res.success) {

                                        window.location.href =
                                            res.redirect_url;

                                    }

                                }

                            });

                        }

                    };

                    const rzp = new Razorpay(options);

                    rzp.open();

                }

            });

        });


        document.addEventListener('DOMContentLoaded', () => {
            // DOM Elements
            const discountInput = document.getElementById('discount-input');
            const applyCouponBtn = document.getElementById('apply-coupon-btn');
            const couponMessage = document.getElementById('coupon-message');
            const discountRow = document.getElementById('discount-row');
            const discountCodeLabel = document.getElementById('discount-code-label');
            const discountValEl = document.getElementById('discount-val');
            const totalValEl = document.getElementById('total-val');
            const removeCouponBtn = document.getElementById('remove-coupon-btn');
            const taxesValEl = document.getElementById('taxes-val');



            // --- Shipping Address Card Selection Logic ---
            const cardNewAddress = document.getElementById('card-new-address');
            const existingAddressCards = document.querySelectorAll('.existing-address-card');
            const shippingFormFields = document.getElementById('shipping-form-fields');

            if (cardNewAddress && shippingFormFields) {
                // Click Add New Address Card
                cardNewAddress.addEventListener('click', () => {
                    document.querySelectorAll('.address-card').forEach(c => c.classList.remove('active'));
                    cardNewAddress.classList.add('active');

                    // Show custom address form fields
                    shippingFormFields.style.display = 'flex';
                });
            }

            existingAddressCards.forEach(card => {
                card.addEventListener('click', (e) => {
                    // Prevent card action buttons from triggering selection
                    if (e.target.tagName === 'A') return;

                    document.querySelectorAll('.address-card').forEach(c => c.classList.remove('active'));
                    card.classList.add('active');

                    // Hide custom address form fields
                    if (shippingFormFields) {
                        shippingFormFields.style.display = 'none';
                    }
                });
            });
        });
    </script>

@if(!empty($beginCheckoutScript))
<script>
    {!! $beginCheckoutScript !!}
</script>
@endif

@endsection