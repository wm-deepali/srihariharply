<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        require_once app_path('Helpers/seo.php');
        $seo = getSeo();
    @endphp

    <title>
        {{ $seo->meta_title ?? $general?->site_name ?? config('app.name') }}
    </title>

    <meta name="description" content="{{ $seo->meta_description ?? $general?->tagline }}">

    @if($seo && $seo->scripts)
        {!! $seo->scripts !!}
    @endif

    <link rel="icon" href="{{ $general?->favicon
    ? asset('storage/' . $general->favicon)
    : asset('favicon.ico') }}">


    <!-- Preload Hero Image -->
    <link rel="preload" href="{{ asset('assets/images/hero/Hero 2.png')}}" as="image">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts: Cinzel for Luxury Headings -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&display=swap" rel="stylesheet">

   <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @include('layouts.tracking-head')

</head>

<body>
        @include('layouts.tracking-body-top')

    <div class="la-pavone-wrapper">

        <!-- 1. STATIC NAVBAR -->
        <nav class="lp-navbar">
            <div class="lp-container lp-nav-content">
                <div class="nav-brand">
                    <a href="{{ route('home') }}"><img src="{{ $general?->logo
    ? asset('storage/' . $general->logo)
    : asset('assets/images/logo.png') }}" alt="{{ $general?->site_name ?? config('app.name') }}"></a>
                </div>

                <ul class="nav-links">
                    <li class="nav-item" id="nav-shop-all">
                        <a href="#">Shop All</a>

                        <div class="mega-menu" id="mega-menu">
                            <div class="mega-menu-inner">

                                {{-- Left side categories --}}
                                <ul class="mega-menu-list left-list">

                                    @foreach($headerCategories as $category)
                                        <li class="mega-menu-item {{ $loop->first ? 'active' : '' }}"
                                            data-target="cat-{{ $category->id }}">
                                            <a href="{{ route('shop.category', $category->slug) }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach

                                </ul>

                                <div class="mega-menu-divider"></div>

                                {{-- Right side subcategories --}}
                                <div class="mega-menu-right-panels" style="display:flex;flex:1">

                                    @foreach($headerCategories as $category)

                                        <ul class="mega-menu-list right-list {{ $loop->first ? 'active-panel' : '' }}"
                                            id="cat-{{ $category->id }}" {{ !$loop->first ? 'style=display:none' : '' }}>

                                            @foreach($category->children as $subCategory)

                                                <li>
                                                    <a
                                                        href="{{ route('shop.category', $category->slug) }}?subcategory={{ $subCategory->id }}">
                                                        {{ $subCategory->name }}
                                                    </a>
                                                </li>

                                            @endforeach

                                        </ul>

                                    @endforeach

                                </div>

                            </div>
                        </div>
                    </li>
                    @foreach($headerCollections as $collection)
                        <li class="nav-item">
                            <a href="{{ route('shop.collection', $collection->slug) }}">
                                {{ $collection->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item"><a href="{{ route('about-us') }}">About La Pavone</a></li>
                </ul>

                <div class="nav-icons">
                    <!--<button aria-label="Search">-->
                    <!--    <img src="{{ asset('assets/images/menu_search.png')}}" alt="Search">-->
                    <!--</button>-->
                    
                    
                  
                   <a href="{{ route('user.wishlist') }}" class="cart-icon" aria-label="Wishlist">
                        <img src="{{ asset('assets/images/menu_wishlist.png')}}" alt="Wishlist">
                          <span class="wishlist-count">
                            {{ $wishlistCount }}
                        </span>
                    </a>
                   
                   <a href="{{ route('cart') }}" class="cart-icon" aria-label="Cart">
                        <img src="{{ asset('assets/images/menu_cart.png') }}" alt="Cart">

                        <span class="cart-count">
                            {{ $globalCartCount }}
                        </span>
                    </a>
                    
                     <a href="{{ route('user.login') }}" aria-label="Account">
                        <img src="{{ asset('assets/images/menu_user.png')}}" alt="Account">
                    </a>
                    <button class="mobile-nav-toggle" aria-label="Toggle Menu">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>


        @yield('content')


        <!-- 9. FOOTER -->
        <footer class="lp-footer">
            <div class="footer-top">
                @if($general?->facebook)
                    <a href="{{ $general->facebook }}" target="_blank" class="footer-social-link" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                @endif

                @if($general?->instagram)
                    <a href="{{ $general->instagram }}" target="_blank" class="footer-social-link">
                        <img src="{{ asset('assets/images/footer_Logo.png')}}" alt="La Pavone Home" loading="lazy">
                    </a>
                @endif
            </div>
       <div class="footer-bottom">
                <div class="lp-container">
                    <div class="footer-links-row">
                        <span class="footer-copyright">&copy;2026, La Pavone</span>
                        <div class="footer-nav">
                            <a href="{{ route('about-us') }}" class="footer-link">About Us</a>
                            <a href="{{ route('contact-us') }}" class="footer-link">Contact Us</a>
                             @php
                            $defaultCourier = \App\Models\Courier::where('is_default', 1)
                                ->where('is_active', 1)
                                ->first();
                        @endphp
                        
                            <a href="{{ route('faqs') }}" class="footer-link">FAQs</a>
                             @if($defaultCourier?->website_url)
                                <a href="{{ $defaultCourier->website_url }}" target="_blank" rel="noopener noreferrer"
                                    class="footer-link">
                                    Track Order
                                </a>
                            @endif
                            <!--<a href="{{ route('blogs') }}" class="">Blogs</a>-->
                            
                         
                            
                            @foreach($footerPages as $page)
                            <a href="{{ route('dynamic.page', \Illuminate\Support\Str::slug($page->page_name)) }}" class="footer-link">
                                {{ $page->heading }}</a>
                                
                                
                                
                                   
                         
                            @endforeach
                            
                            
                            <!--   <div class="footer-dropdown">-->
                               
                            <!--             <a href="{{ route('blogs') }}" class="footer-dropdown-toggle" > <span>Blogs</span>  </a>-->
                            <!--    <div class="footer-dropdown-menu">-->
                            <!--        <a href="#" class="footer-dropdown-item">English</a>-->
                            <!--        <a href="#" class="footer-dropdown-item">Hindi</a>-->
                            <!--    </div>-->
                            <!--</div>-->
                            
                <div class="footer-blog-dropdown">
    <a href="javascript:void(0)" class="footer-blog-toggle">
        <span>
            Blogs
            <i class="fa-solid fa-chevron-down"></i>
        </span>
    </a>

    <div class="footer-blog-menu">
        <a href="{{ route('blogs') }}" class="footer-blog-item">English</a>
        <a href="{{ route('blogs') }}" class="footer-blog-item">Hindi</a>
    </div>
</div>
                        </div>
                    </div>
                    <div class="footer-address">
                        @if(!empty($general?->business_address))
                        <p>{{ $general->business_address }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    </div>
<style>
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .cart-icon {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .cart-icon img {
            width: 22px;
            height: 22px;
        }

        .cart-count {
            position: absolute;
            top: -6px;
            right: -6px;

            width: 20px;
            height: 20px;

            border-radius: 50%;
             background: #002a26;
            /* gold */
            color: #fff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 11px;
            font-weight: 700;

            box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
            border: 2px solid #fff;
        }

       .wishlist-count {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #002a26;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
    border: 2px solid #fff;
}

    </style>
    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/main.js')}}"></script>
    
    <!-- GSAP & ScrollTrigger -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
@include('layouts.tracking-body-bottom')

</body>

</html>