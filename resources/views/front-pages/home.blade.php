@extends('layouts.app')
@section('content')

    <style>
        .price-mrp {
            text-decoration: line-through;
            display: block;
        }

        .price-discounted {
            /* color: #dc3545; */
            display: block;
        }
    </style>

    <!-- 2. HERO SECTION -->
    <section class="hero-section" id="hero">
        <img src="{{ $hero->background_image ? asset('storage/' . $hero->background_image) : 'assets/images/banners/banner_img.png' }}"
            alt="{{ $hero->heading_line1 }}" class="hero-bg" loading="eager">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-heading">
                {{ $hero->heading_line1 }}<br>{{ $hero->heading_line2 }}
            </h1>
            <div class="hero-buttons">
                <a href="{{ $hero->btn1_url }}" class="lp-btn">{{ $hero->btn1_label }}</a>
                <a href="{{ $hero->btn2_url }}" class="lp-btn">{{ $hero->btn2_label }}</a>
            </div>
        </div>
    </section>

    <!-- 3. FRAGRANCE COLLECTION SECTION -->
    <section class="collection-section lp-container js-observe">
        <div class="collection-header js-fade-in">
            <h2 class="section-heading">Introducing fragrance collection 26'</h2>
        </div>

        <div class="carousel-wrapper">
            <div class="carousel-controls">
                <button class="carousel-btn" id="btn-prev" aria-label="Previous">
                    <img src="{{ asset('assets/images/products/prives.png') }}" alt="Previous">
                </button>
                <button class="carousel-btn" id="btn-next" aria-label="Next">
                    <img src="{{ asset('assets/images/products/next.png') }}" alt="Next">
                </button>
            </div>

            <div class="carousel-track" id="product-track">
                @foreach($featuredProducts as $product)
                    @php
                        $defaultImg = $product->images->firstWhere('image_type', 'default');
                        $hoverImg = $product->images->firstWhere('image_type', 'hover');
                    @endphp
                    <div class="product-card js-card-slide">
                        @if($product->mrp && $product->mrp > $product->price)
                            @php
                                $discountPercent = round((($product->mrp - $product->price) / $product->mrp) * 100);
                                $tagColor = $product->detail_page_color ?? '#dc3545';
                            @endphp
                            <span class="product-tag" style="background-color: {{ $tagColor }}; border-color: {{ $tagColor }};">{{ $discountPercent }}% OFF</span>
                            <span class="product-hover-tag" style="background-color: {{ $tagColor }}; border-color: {{ $tagColor }};">{{ $discountPercent }}%
                                OFF</span>
                        @endif
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
                            </div>

                            <div class="product-actions">
                                {{-- Wishlist: AJAX se add, icon toggle hoga --}}
                                <button
                                    class="action-btn btn-wishlist {{ in_array($product->id, $wishlistProductIds ?? []) ? 'active' : '' }}"
                                    data-product-id="{{ $product->id }}" aria-label="Add to Wishlist">
                                    <svg class="wishlist-icon-default" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="#666" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
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

                                {{-- Add to Cart --}}
                                <button class="action-btn btn-cart" data-product-id="{{ $product->id }}"
                                    aria-label="Add to Cart">
                                    <img src="{{ asset('assets/images/products/add_to_cart.png') }}" alt="Add to Cart">
                                </button>
                            </div>
                        </div>

                        <div class="productcard_bottom">
                            <div class="product-category-hover">
                                {{ $product->category?->name }}
                                @if($product->sub_title)
                                    | {{ $product->sub_title }}
                                @endif
                            </div>
                            <div class="product-category-hover2">
                                @if($product->mrp && $product->mrp > $product->price)
                                    <div class="price-mrp">₹{{ number_format($product->mrp, 2) }}</div>
                                    <div class="price-discounted">₹{{ number_format($product->price, 2) }}</div>
                                @else
                                    <div class="price-discounted">₹{{ number_format($product->price, 2) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. BANNER 1 -->
    <div class="static-banner static-banner-1" @if($banner->background_image)
    style="background-image: url('{{ asset('storage/' . $banner->background_image) }}');" @endif>
        <div class="static-banner-overlay"></div>
        <h2 class="section-heading static-banner-heading">{{ $banner->heading }}</h2>
    </div>

    <!-- 6. MEN / WOMEN CATEGORY SECTION -->
    <section class="category-section js-observe">
        <div class="category-intro text-center mb-5 mx-auto" style="max-width: 800px; padding: 40px 20px;">
            <p class="category-intro-text">
                Today's India is shaped by both tradition and modern living. La Pavone is created for those who
                appreciate quality, value thoughtful choices, and find beauty in the details that feel personal.
            </p>
        </div>
        <div class="category-grid">
            @foreach($featuredCategories as $category)
                <div class="category-block {{ $loop->odd ? 'js-slide-left' : 'js-slide-right' }}">
                    <img src="{{ $category->square_image
                ? asset('storage/' . $category->square_image)
                : asset('assets/images/categories/default.jpg') }}" alt="{{ $category->name }}" loading="lazy">
                    <div class="category-content">
                        <h3 class="category-heading text-white">{{ strtoupper($category->name) }}</h3>
                        <a href="{{ route('shop.category', $category->slug) }}" class="lp-btn">Shop Now</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 7. BANNER 2 (Testimonial) -->
    <div class="static-banner static-banner-2 mt-md-5" @if($testimonial->background_image)
    style="background-image: url('{{ asset('storage/' . $testimonial->background_image) }}');" @endif>
        <div class="testimonial-content">
            <div class="testimonial-text-wrapper">
                <div class="quote-mark">"</div>
                <h2 class="testimonial-heading">
                    {{ $testimonial->quote_line1 }}
                    <span>{{ $testimonial->quote_line2 }}</span>
                </h2>
            </div>
            <p class="testimonial-author">{{ $testimonial->author }}</p>
        </div>
    </div>

    <!-- 8. AUDIO SECTION -->
    <section class="audio-section js-audio-observe" id="audio-section">
        <h2 class="section-heading">{{ $audio->heading }}</h2>
        <div class="audio-image-wrapper mt-4">
            <img src="{{ $audio->section_image ? asset('storage/' . $audio->section_image) : 'assets/images/banners/last_imgsection.png' }}"
                alt="{{ $audio->heading }}" loading="lazy">
        </div>
        <audio id="ambient-audio" loop preload="none">
            @if($audio->audio_file)
                <source src="{{ asset('storage/' . $audio->audio_file) }}" type="audio/mpeg">
            @else
                <source src="{{ asset('assets/images/home_footer_audio.mpeg') }}" type="audio/mpeg">
            @endif
        </audio>
    </section>



    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>

        /*
        |----------------------------------------------------------------------
        | ADD TO CART
        | Route   : cart.add  (POST)
        | Sends   : product_id, quantity
        | Gets    : status, message, cart_count
        |----------------------------------------------------------------------
        */
        $(document).on('click', '.btn-cart', function (e) {

            e.preventDefault();
            e.stopPropagation();

            const $btn = $(this);
            const productId = $btn.data('product-id');

            if ($btn.data('loading')) return;
            $btn.data('loading', true).prop('disabled', true);

            $.ajax({
                url: "{{ route('cart.add') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    quantity: 1,
                },
                success: function (response) {
                    if (response.status) {
                        // Header cart icon count update
                        $('.cart-count').text(response.cart_count);
                        fireTrackingEvents(response.tracking_events);

                        Swal.fire({
                            icon: 'success',
                            title: 'Added to Cart',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                    } else {
                        Swal.fire({ icon: 'warning', title: response.message });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: xhr.responseJSON?.message ?? 'Something went wrong.',
                    });
                },
                complete: function () {
                    $btn.data('loading', false).prop('disabled', false);
                },
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