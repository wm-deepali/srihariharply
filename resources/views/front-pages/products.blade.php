@extends('layouts.app')
@section('content')

    <div class="shopall-page-wrapper">

        <!-- CATEGORY HERO BANNER -->
        <section class="shopall-hero-banner" @if($category->horizontal_image) style="background-image: url('{{ asset('storage/' . $category->horizontal_image) }}');
           background-size: cover; background-position: center; position: relative;" @endif>
            @if($category->horizontal_image)
                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.35);"></div>
            @endif
            <div class="lp-container" style="position:relative;z-index:1;">
                <div class="shopall-breadcrumbs">
                    <a href="{{ url('/') }}" style="color:inherit;">Home</a> /
                    {{ $category->name }}
                </div>
                <h1 class="shopall-hero-title">{{ $category->name }}</h1>
                @if($category->description)
                    <p style="margin-top:8px;opacity:.85;font-size:15px;max-width:520px;">
                        {{ $category->description }}
                    </p>
                @endif
            </div>
        </section>

        <!-- CONTROLS BAR -->
        <section class="controls-section">
            <div class="lp-container d-flex  flex-md-row justify-content-between align-items-center gap-3">

                <!-- Subcategory Filter Tabs -->
                <div class="filter-group d-flex flex-wrap gap-2" id="filter-tabs">

                    <button class="filter-tab {{ !request('subcategory') ? 'active' : '' }}" onclick="applyFilter('', '')">
                        All
                    </button>

                    @foreach($subcategories as $sub)
                        <button class="filter-tab {{ request('subcategory') == $sub->id ? 'active' : '' }}"
                            onclick="applyFilter('{{ $sub->id }}', '{{ $sub->slug }}')">
                            {{ $sub->name }}
                        </button>
                    @endforeach

                </div>

                <!-- Sort -->
                <div class="sort-group">
                    <span class="sort-label">Sort By:</span>
                    <div class="sort-select-wrapper">
                        <select id="sort-select" class="sort-select" onchange="applySort(this.value)">
                            <option value="featured" {{ request('sort') === 'featured' || !request('sort') ? 'selected' : '' }}>Featured</option>
                            <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low to
                                High</option>
                            <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High to
                                Low</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRODUCT GRID -->
        <section class="catalog-section">
            <div class="lp-container">

                @if($products->isEmpty())
                    <div style="text-align:center;padding:60px 20px;color:#888;">
                        <p style="font-size:18px;">No products found in this category.</p>
                    </div>
                @else
                    <div class="row g-4 justify-content-center" id="catalog-grid">
                        @foreach($products as $product)
                            @php
                                $defaultImg = $product->images->firstWhere('image_type', 'default');
                                $hoverImg = $product->images->firstWhere('image_type', 'hover');
                            @endphp
                            <div class="col-12 col-md-4">
                                <div class="product-card is-visible">
                                    <div class="product-image-wrap"
                                        onclick="window.location.href='{{ route('shop.product', $product->slug) }}'">
                                        <img src="{{ $defaultImg ? asset('storage/' . $defaultImg->image) : asset('assets/images/placeholder.png') }}"
                                            alt="{{ $product->name }}" class="product-image-primary" loading="lazy">
                                        <img src="{{ $hoverImg ? asset('storage/' . $hoverImg->image) : ($defaultImg ? asset('storage/' . $defaultImg->image) : asset('assets/images/placeholder.png')) }}"
                                            alt="{{ $product->name }} Hover" class="product-image-secondary" loading="lazy">
                                    </div>
                                    <div class="product-info">
                                        <div class="product-details">
                                            <div class="product-name"
                                                onclick="window.location.href='{{ route('shop.product', $product->slug) }}'">
                                                {{ $product->name }}
                                            </div>
                                            <div class="product-price-card">₹{{ number_format($product->price, 0) }}</div>
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
                                            <button class="action-btn btn-cart" data-product-id="{{ $product->id }}"
                                                aria-label="Add to Cart">
                                                <svg class="plus-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="#666" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg>
                                                <svg class="cart-icon" style="display:none;" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="#1F5552" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="9" cy="21" r="1"></circle>
                                                    <circle cx="20" cy="21" r="1"></circle>
                                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Reads current URL and rebuilds it with updated query params
        function buildUrl(params) {
            const url = new URL(window.location.href);
            Object.entries(params).forEach(([key, val]) => {
                if (val === '' || val === null) {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key, val);
                }
            });
            return url.toString();
        }

        // PHP se JS mein flag pass karo
        const isShopAll = {{ ($isShopAll ?? false) ? 'true' : 'false' }};

        function buildUrl(params) {
            const url = new URL(window.location.href);
            Object.entries(params).forEach(([key, val]) => {
                if (val === '' || val === null) url.searchParams.delete(key);
                else url.searchParams.set(key, val);
            });
            return url.toString();
        }

        function applyFilter(id, slug) {
            if (isShopAll) {
                // Shop All page → category tab click → category URL pe jao
                if (slug) {
                    window.location.href = "{{ url('/category') }}/" + slug;
                } else {
                    // "All" tab → shop all wapas, filter hata do
                    window.location.href = "{{ route('shop.all') }}";
                }
            } else {
                // Category page → subcategory filter
                window.location.href = buildUrl({ subcategory: id });
            }
        }

        function applySort(sortValue) {
            window.location.href = buildUrl({ sort: sortValue === 'featured' ? '' : sortValue });
        }

        // Changing sort dropdown — keeps current subcategory filter, updates sort
        function applySort(sortValue) {
            window.location.href = buildUrl({ sort: sortValue === 'featured' ? '' : sortValue });
        }


        $(document).on('click', '.btn-cart', function (e) {

            e.preventDefault();
            e.stopPropagation();

            let button = $(this);
            let productId = button.data('product-id');

            $.ajax({
                url: "{{ route('cart.add') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    quantity: 1
                },

                success: function (response) {

                    button.find('.plus-icon').hide();
                    button.find('.cart-icon').show();

                    if ($('.cart-count').length) {
                        $('.cart-count').text(response.cart_count);
                    }
   fireTrackingEvents(response.tracking_events); 
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },

                error: function (xhr) {

                    let message = 'Something went wrong';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: message
                    });
                }

            });

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
                        $icon.css('opacity', '0.6');

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
                        });

                    }
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON?.message ?? 'Something went wrong.';
                    Swal.fire({ icon: 'error', title: 'Oops!', text: msg });

                },
                complete: function () {
                    $btn.data('loading', false).prop('disabled', false);
                },
            });
        });


    </script>
@endsection