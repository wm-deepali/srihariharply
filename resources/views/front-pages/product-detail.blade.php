@extends('layouts.app')
@section('content')

    @php
        $bannerImages = $product->images->where('image_type', 'banner')->values();
        $defaultImg = $product->images->firstWhere('image_type', 'default');
        $storyImg = $product->images->firstWhere('image_type', 'story');

        if ($bannerImages->isEmpty() && $defaultImg) {
            $bannerImages = collect([$defaultImg]);
        }

    @endphp

    <style>
        /* ── Qty Controls ── */
        .qty-controls {
            border: 1.5px solid #1F5552;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            align-items: center;
            /*padding: 10px 24px;*/
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            display: flex;


        }

        .qty-btn {
            background: transparent;
            border: none;
            color: #1F5552;
            font-size: 22px;
            font-weight: 500;
            width: 40px;
            /*height: 44px;*/
            cursor: pointer;
            transition: background 0.15s;
            line-height: 1;
        }

        .qty-btn:hover {
            background: #e8f1f0;
        }

        .qty-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        .qty-value {
            display: inline-block;
            min-width: 36px;
            text-align: center;
            font-size: 24px;
            font-weight: 500;
            color: #1F5552;
        }

        /* ── View Cart Button ── */
        .btn-view-cart {
            background-color: #1F5552;
            color: #fff !important;
            border: 1px solid transparent;
            padding: 10px 24px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .btn-view-cart:hover {
            background: #e8f1f0;
            color: #1F5552;
        }

        /* ── Add To Bag Wrapper ── */
        .add-to-bag-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* .qty-btn.delete-mode {
            color: #e53e3e;
        } */

        .qty-btn.delete-mode:hover {
            background: #fff5f5;
        }

        #ps-dots-right .dot {
            background-color:
                {{$product->detail_page_color ?? '#B8832F'}}
            ;
            border-color:
                {{$product->detail_page_color ?? '#B8832F'}}
        }

        #ps-dots-right .dot.active {
            background-color: #fff;
            border-color:
                {{$product->detail_page_color ?? '#B8832F'}}
            ;
        }

        /* ── Price (MRP / Discounted) ── */
        .price-mrp {
            text-decoration: line-through;
            display: block;
        }

        .price-discounted {
            display: block;
        }

    
    </style>

    <div class="product-page-wrapper">

        <!-- PRODUCT HERO SLIDER -->
        <section class="product-hero-slider split-slider">
            <div class="product-slider-half">
                <div class="product-slider-container" id="product-slider-right">
                    @forelse($bannerImages as $img)
                        <div class="product-slide">
                            <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}">
                        </div>
                    @empty
                        <div class="product-slide">
                            <img src="{{ asset('assets/images/placeholder.png') }}" alt="{{ $product->name }}">
                        </div>
                    @endforelse
                </div>
                @if($bannerImages->count() > 1)
                    <div class="carousel-controls" style="top: 50%; padding: 0 40px;">
                        <button class="control-btn" style="pointer-events: auto;" id="ps-prev-right" aria-label="Previous">
                            <img src="{{ asset('assets/images/white_prives_icon.png') }}" alt="Previous">
                        </button>
                        <button class="control-btn" style="pointer-events: auto;" id="ps-next-right" aria-label="Next">
                            <img src="{{ asset('assets/images/white_next_icon.png') }}" alt="Next">
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <!-- SLIDER DOTS -->
        @if($bannerImages->count() > 1)
            <div class="slider-dots-container" style="text-align: center; padding: 20px 0; background: #fff;">
                <div class="slider-dots" id="ps-dots-right"
                    style="position: static; transform: none; display: inline-flex; justify-content: center;">
                    @foreach($bannerImages as $i => $img)
                        <span class="dot {{ $i === 0 ? 'active' : '' }}"></span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- PRODUCT DETAILS SECTION -->
        <section class="product-details-section">
            <div class="lp-container">
                <div class="product-details-grid">
                    <div class="details-left">
                        <div class="product-header">
                            <h1 class="product-title">
                                {{ $product->name }}
                                @if($product->weight)
                                    <span class="product-size">{{ $product->weight }}ml</span>
                                @endif
                            </h1>
                            <p class="product-subtitle" style="color: {{ $product->detail_page_color ?? '#B8832F' }};">
                                {{ $product->category->name }}
                                @if($product->sub_title)
                                    | {{ $product->sub_title }}
                                @endif
                            </p>
                        </div>

                        <div class="details-right">
                            <div class="product-price">
                                @if($product->mrp && $product->mrp > $product->price)
                                    @php
                                        $discountPercent = round((($product->mrp - $product->price) / $product->mrp) * 100);
                                    @endphp
                                    <span class="price-mrp">₹{{ number_format($product->mrp, 2) }}</span>
                                    <span class="price-discounted">₹{{ number_format($product->price, 2) }}</span>
                                    <span class="product-tag" style="background-color: {{ $product->detail_page_color ?? '#dc3545' }}; border-color: {{ $product->detail_page_color ?? '#dc3545' }};">{{ $discountPercent }}% OFF</span>
                                @else
                                    <span class="price-discounted">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>

                            {{-- ── Add To Bag / Qty Controls / View Cart ── --}}
                            <div class="add-to-bag-wrapper">

                                @php
                                    $inCart = !empty($cartItem);
                                    $cartQty = $cartItem->quantity ?? ($cartItem['quantity'] ?? 1);
                                    $cartItemId = $cartItem->id ?? ($cartItem['id'] ?? null);
                                @endphp

                                {{-- Step 1: Add To Bag button --}}
                                <button class="btn-add-bag" id="add-to-bag-btn" data-product-id="{{ $product->id }}"
                                    style="{{ $inCart ? 'display:none' : '' }}">
                                    <span style="display: flex; align-items: center; gap: 8px;">
                                        <img src="{{ asset('assets/images/products/add_to_cart.png') }}" alt="Bag"
                                            class="bag-icon">
                                        Add To Bag
                                    </span>
                                </button>


                                {{-- Step 2: Qty Controls — cart_item_id JS mein store hoga --}}
                                <div class="qty-controls" id="qty-controls" data-cart-item-id="{{ $cartItemId }}"
                                    style="{{ $inCart ? 'display:flex' : 'display:none' }}">
                                    <button class="qty-btn" type="button" id="qty-minus">
                                        <i class="fa-solid {{ $cartQty <= 1 ? 'fa-trash' : 'fa-minus' }}"
                                            id="qty-minus-icon"></i>
                                    </button>

                                    <span class="qty-value" id="qty-display">
                                        {{ $cartQty }}
                                    </span>

                                    <button class="qty-btn" type="button" id="qty-plus">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>


                                {{-- Step 2: View Cart button --}}
                                <a href="{{ route('cart') }}" class="btn-view-cart" id="view-cart-btn"
                                    style="{{ $inCart ? 'inline-flex' : 'display:none' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    View Cart
                                </a>

                            </div>
                            {{-- ── /Add To Bag ── --}}

                        </div>
                    </div>

                    <div class="product-accordions">

                        @if($product->description)
                            <div class="accordion-item">
                                <div class="accordion-header" style="color: {{ $product->detail_page_color ?? '#B8832F' }};">
                                    DESCRIPTION <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <div class="accordion-body" style="display:block;">
                                    <p>{!! $product->description !!}</p>
                                </div>
                            </div>
                        @endif

                                             
@if(!empty($product->product_notes))
    <div class="accordion-item">
        <div class="accordion-header"
            style="color: {{ $product->detail_page_color ?? '#B8832F' }};">
            NOTES <i class="fa-solid fa-chevron-down"></i>
        </div>

        <div class="accordion-body">
            <div class="notes-grid">

                @foreach($product->product_notes as $note)
                    <div class="note-col">
                        <div class="note-title">
                            {{ $note['title'] ?? '' }}
                        </div>

                        <div class="note-desc">
                            {{ $note['subtitle'] ?? '' }}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endif

                        @if($product->how_to_use)
                            <div class="accordion-item">
                                <div class="accordion-header" style="color: {{ $product->detail_page_color ?? '#B8832F' }};">
                                    HOW TO USE <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <div class="accordion-body">
                                    <p>{!! $product->how_to_use !!}</p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>

        <!-- THE STORY SECTION -->
        @if($product->the_story)
            <section class="product-story-section">
                <div class="lp-container text-center">
                    <h2 class="story-heading">THE STORY</h2>
                    <p class="story-text">{!! nl2br(e($product->the_story)) !!}</p>
                </div>
                @php $displayStoryImg = $storyImg ?? $bannerImages->skip(1)->first() ?? $defaultImg; @endphp
                @if($displayStoryImg)
                    <div class="story-img-container">
                        <img src="{{ asset('storage/' . $displayStoryImg->image) }}" alt="The Story" class="story-full-img">
                    </div>
                @endif
            </section>
        @endif

        <!-- SIMILAR FRAGRANCES SECTION -->
        @if($similarProducts->isNotEmpty())
            <section class="similar-fragrances-section">
                <div class="lp-container">
                    <div class="section-header-flex">
                        <h2 class="section-heading text-left" style="margin-bottom:0;">SIMILAR FRAGRANCES</h2>
                    </div>

                    <div class="carousel-wrapper" style="margin-top: 30px;">
                        <div class="carousel-controls">
                            <button class="carousel-btn" id="btn-similar-prev" aria-label="Previous">
                                <img src="{{ asset('assets/images/products/prives.png') }}" alt="Previous">
                            </button>
                            <button class="carousel-btn" id="btn-similar-next" aria-label="Next">
                                <img src="{{ asset('assets/images/products/next.png') }}" alt="Next">
                            </button>
                        </div>
                        <div class="carousel-track" id="similar-track">
                            @foreach($similarProducts as $similar)
                                @php
                                    $simDefault = $similar->images->firstWhere('image_type', 'default');
                                    $simHover = $similar->images->firstWhere('image_type', 'hover');
                                @endphp
                                <div class="product-card is-visible"
                                    onclick="window.location.href='{{ route('shop.product', $similar->slug) }}'">
                                    @if($similar->mrp && $similar->mrp > $similar->price)
                                        @php
                                            $simDiscountPercent = round((($similar->mrp - $similar->price) / $similar->mrp) * 100);
                                            $simTagColor = $similar->detail_page_color ?? '#dc3545';
                                        @endphp
                                        <span class="product-tag" style="background-color: {{ $simTagColor }}; border-color: {{ $simTagColor }};">{{ $simDiscountPercent }}% OFF</span>
                                        <span class="product-hover-tag" style="background-color: {{ $simTagColor }}; border-color: {{ $simTagColor }};">{{ $simDiscountPercent }}%
                                            OFF</span>
                                    @endif
                                    <div class="product-image-wrap">
                                        <img src="{{ $simDefault ? asset('storage/' . $simDefault->image) : asset('assets/images/placeholder.png') }}"
                                            alt="{{ $similar->name }}" class="product-image-primary" loading="lazy">
                                        <img src="{{ $simHover ? asset('storage/' . $simHover->image) : ($simDefault ? asset('storage/' . $simDefault->image) : asset('assets/images/placeholder.png')) }}"
                                            alt="{{ $similar->name }} Hover" class="product-image-secondary" loading="lazy">
                                    </div>
                                    <div class="product-info">
                                        <div class="product-details">
                                            <div class="product-name">{{ $similar->name }}</div>
                                            <div class="product-price-card">
                                                @if($similar->mrp && $similar->mrp > $similar->price)
                                                    <span class="price-mrp">₹{{ number_format($similar->mrp, 0) }}</span>
                                                    <span class="price-discounted">₹{{ number_format($similar->price, 0) }}</span>
                                                @else
                                                    <span class="price-discounted">₹{{ number_format($similar->price, 0) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-actions">
                                            <button
                                                class="action-btn btn-wishlist {{ in_array($product->id, $wishlistProductIds ?? []) ? 'active' : '' }}"
                                                data-product-id="{{ $product->id }}" aria-label="Add to Wishlist">
                                                <svg class="wishlist-icon-default" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="#666" stroke-width="1.2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path
                                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                                    </path>
                                                </svg>
                                                <svg class="wishlist-icon-active" style="display:none;" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="#dc3545" stroke="#dc3545" stroke-width="1.2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button class="action-btn btn-add" aria-label="Add" onclick="event.stopPropagation();">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666"
                                                    stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- FAQS SECTION -->
        <section class="faqs-section">
            <div class="lp-container">
                <h2 class="faq-main-heading">FAQs</h2>
                <div class="faq-accordion-list">
                    @forelse($faqs as $faq)
                        <div class="faq-item">
                            <div class="faq-header">
                                {{ $faq->question }} <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="faq-body">{{ $faq->answer }}</div>
                        </div>
                    @empty
                        {{-- No FAQs --}}
                    @endforelse
                </div>
            </div>
        </section>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {

            let cartItemId = $('#qty-controls').data('cart-item-id') || null;
            let currentQty = parseInt($('#qty-display').text()) || 1;
            let isUpdating = false;

            const $addBtn = $('#add-to-bag-btn');
            const $qtyControls = $('#qty-controls');
            const $viewCart = $('#view-cart-btn');
            const $qtyDisplay = $('#qty-display');
            const $qtyMinus = $('#qty-minus');
            const $qtyMinusIcon = $('#qty-minus-icon');
            const $qtyPlus = $('#qty-plus');

            if (cartItemId) {
                updateMinusIcon(currentQty);
            }
            // ── Icon update helper ──
            function updateMinusIcon(qty) {
                if (qty <= 1) {
                    $qtyMinus.addClass('delete-mode');
                    $qtyMinusIcon.removeClass('fa-minus').addClass('fa-trash');
                } else {
                    $qtyMinus.removeClass('delete-mode');
                    $qtyMinusIcon.removeClass('fa-trash').addClass('fa-minus');
                }
            }

            // ── Add ke baad UI switch ──
            function showQtyState(qty, itemId) {
                cartItemId = itemId;
                currentQty = qty;
                $qtyDisplay.text(currentQty);
                $addBtn.hide();
                $qtyControls.css('display', 'flex');
                $viewCart.css('display', 'inline-flex');
                updateMinusIcon(currentQty);   // ← icon set karo
            }

            // ── Wapas Add To Bag state ──
            function resetToAddState() {
                cartItemId = null;
                currentQty = 1;
                $qtyControls.hide();
                $viewCart.hide();
                $addBtn.prop('disabled', false).show();
                $('.cart-count').text(parseInt($('.cart-count').text() || 0) - 0); // count server se aayega
            }

            // ── ADD TO BAG ──
            $addBtn.on('click', function () {
                $addBtn.prop('disabled', true);
                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: $addBtn.data('product-id'),
                        quantity: 1,
                    },
                    success: function (response) {
                        if (response.status) {
                            showQtyState(1, response.cart_item_id);
                            $('.cart-count').text(response.cart_count);
                               fireTrackingEvents(response.tracking_events); 
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                            });
                        } else {
                            $addBtn.prop('disabled', false);
                            Swal.fire({ icon: 'warning', title: response.message, confirmButtonColor: '#1F5552' });
                        }
                    },
                    error: function (xhr) {
                        $addBtn.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: xhr.responseJSON?.message ?? 'Something went wrong.',
                            confirmButtonColor: '#1F5552',
                        });
                    },
                });
            });

            // ── QTY MINUS / DELETE ──
            $qtyMinus.on('click', function () {
                if (isUpdating) return;

                // Qty 1 hai → DELETE mode
                if (currentQty <= 1) {
                    Swal.fire({
                        title: 'Remove Item?',
                        text: 'Are you sure you want to remove this item from your cart?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53e3e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Remove',
                        cancelButtonText: 'Cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            callRemoveItem();
                        }
                    });
                    return;
                }

                callUpdateQty('minus');
            });

            // ── QTY PLUS ──
            $qtyPlus.on('click', function () {
                if (isUpdating) return;
                callUpdateQty('plus');
            });

            // ── AJAX: Update Quantity ──
            function callUpdateQty(action) {
                isUpdating = true;
                $qtyMinus.prop('disabled', true);
                $qtyPlus.prop('disabled', true);

                $.ajax({
                    url: "{{ route('cart.update-quantity') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        item_id: cartItemId,
                        action: action,
                    },
                    success: function (response) {
                        if (response.status) {
                            currentQty = response.quantity;
                            $qtyDisplay.text(currentQty);
                            $qtyMinus.prop('disabled', false);
                            $qtyPlus.prop('disabled', false);
                            updateMinusIcon(currentQty);   // ← har update pe icon check karo
                            $('.cart-count').text(response.cart_count);
                        } else {
                            $qtyMinus.prop('disabled', false);
                            $qtyPlus.prop('disabled', false);
                            updateMinusIcon(currentQty);
                            Swal.fire({
                                icon: 'warning',
                                title: response.message ?? 'Limit reached',
                                timer: 1800,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function (xhr) {
                        $qtyMinus.prop('disabled', false);
                        $qtyPlus.prop('disabled', false);
                        updateMinusIcon(currentQty);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: xhr.responseJSON?.message ?? 'Quantity update failed.',
                            confirmButtonColor: '#1F5552',
                        });
                    },
                    complete: function () {
                        isUpdating = false;
                    },
                });
            }

            // ── AJAX: Remove Item ──
            function callRemoveItem() {
                isUpdating = true;
                $qtyMinus.prop('disabled', true);
                $qtyPlus.prop('disabled', true);

                $.ajax({
                    url: "{{ route('cart.remove', ['id' => '__ID__']) }}"
                        .replace('__ID__', cartItemId),
                    type: 'POST',                                          // ← POST hi bhejo
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE',   // ← Laravel method spoofing
                    },
                    success: function (response) {
                        if (response.status) {
                            $('.cart-count').text(response.cart_count);
                             fireTrackingEvents(response.tracking_events); 
                            resetToAddState();
                            Swal.fire({
                                icon: 'success',
                                title: response.message ?? 'Item removed',
                                timer: 1500,
                                showConfirmButton: false,
                            });
                        } else {
                            Swal.fire({ icon: 'warning', title: response.message, confirmButtonColor: '#1F5552' });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: xhr.responseJSON?.message ?? 'Remove failed.',
                            confirmButtonColor: '#1F5552',
                        });
                    },
                    complete: function () {
                        isUpdating = false;
                        $qtyMinus.prop('disabled', currentQty <= 1);
                        $qtyPlus.prop('disabled', false);
                    },
                });
            }
        });

        $(document).on('click', '.btn-wishlist', function (e) {

            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            const $icon = $btn.find('.wishlist-icon');
            const productId = $btn.data('product-id');

            if ($btn.data('loading')) return;
            $btn.data('loading', true).prop('disabled', true);

            $.ajax({
                url: "{{ route('wishlist.add') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                },
                success: function (response) {

                    if (response.wishlist_count !== undefined) {
                        $('.wishlist-count').text(response.wishlist_count);
                    }
 fireTrackingEvents(response.tracking_events);
                    if (response.status) {

                        $btn.addClass('active');
                        $icon.css('opacity', '1');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                        });

                    } else {

                        Swal.fire({
                            icon: 'warning',   // or 'error'
                            title: 'Wishlist',
                            text: response.message,
                            confirmButtonColor: '#1F5552',
                        });

                    }
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON?.message ?? 'Something went wrong.';
                    Swal.fire({ icon: 'error', title: 'Oops!', text: msg, confirmButtonColor: '#1F5552' });

                },
                complete: function () {
                    $btn.data('loading', false).prop('disabled', false);
                },
            });
        });


    </script>

@if(!empty($viewItemScript))
<script>
    {!! $viewItemScript !!}
</script>
@endif
@endsection