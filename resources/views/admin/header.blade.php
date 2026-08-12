<!-- fixed-top-->
<div class="row d-none">
    <div class="col-10">

        @if(session('success'))
            <div class="alert alert-info alert-dismissible fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">&times;</a>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade in">
                <a href="javascript:void(0);" class="close" data-dismiss="alert">&times;</a>
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</div>

<!-- fixed-top-->

<div id='cssmenu'>
    <ul class="pt-0">

        {{-- DASHBOARD --}}
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
        </li>

        {{-- LOGO MANAGEMENT --}}
        <li class="{{ request()->routeIs('admin.logo.*') ? 'active' : '' }}">
            <a href="{{ route('admin.logo.index') }}">
                <i class="fa-solid fa-image"></i> Logo Management
            </a>
        </li>

        {{-- SLIDER MANAGEMENT --}}
        <li class="{{ request()->routeIs('admin.slider.*') ? 'active' : '' }}">
            <a href="{{ route('admin.slider.index') }}">
                <i class="fa-solid fa-images"></i> Slider Management
            </a>
        </li>

        {{-- HOME PAGE MANAGEMENT --}}
        <li class="has-sub {{ request()->routeIs('admin.our-services.*', 'admin.introduction.*', 'admin.client.*') ? 'active' : '' }}">
            <a href="#">
                <i class="fa-solid fa-house"></i> Home Page Mgmt
            </a>
            <ul>
                <li class="{{ request()->routeIs('admin.our-services.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.our-services.index') }}">Our Product</a>
                </li>
                <li class="{{ request()->routeIs('admin.introduction.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.introduction.index') }}">Introduction</a>
                </li>
                <li class="{{ request()->routeIs('admin.client.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.client.index') }}">Client Gallery</a>
                </li>
            </ul>
        </li>

        {{-- ABOUT US PAGE MANAGEMENT --}}
        <li class="{{ request()->routeIs('admin.about-us.*') ? 'active' : '' }}">
            <a href="{{ route('admin.about-us.index') }}">
                <i class="fa-solid fa-circle-info"></i> About Us Page Mgmt
            </a>
        </li>

        {{-- PRODUCT CATEGORY MANAGEMENT --}}
        <li class="has-sub {{ request()->routeIs('admin.product-category.*', 'admin.brand.*', 'admin.category-details.*') ? 'active' : '' }}">
            <a href="#">
                <i class="fa-solid fa-boxes-stacked"></i> Product Category Mgmt
            </a>
            <ul>
                <li class="{{ request()->routeIs('admin.product-category.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.product-category.index') }}">Category</a>
                </li>
                <li class="{{ request()->routeIs('admin.brand.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.brand.index') }}">Brand</a>
                </li>
                <li class="{{ request()->routeIs('admin.category-details.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.category-details.index') }}">Product</a>
                </li>
            </ul>
        </li>

        {{-- PICTURE GALLERY --}}
        <li class="has-sub {{ request()->routeIs('admin.gallery.*', 'admin.gallery-details.*') ? 'active' : '' }}">
            <a href="#">
                <i class="fa-solid fa-camera-retro"></i> Picture Gallery
            </a>
            <ul>
                <li class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.gallery.index') }}">Image Category</a>
                </li>
                <li class="{{ request()->routeIs('admin.gallery-details.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.gallery-details.index') }}">Image Gallery</a>
                </li>
            </ul>
        </li>

        {{-- TESTIMONIAL --}}
        <li class="{{ request()->routeIs('admin.testimonial.*') ? 'active' : '' }}">
            <a href="{{ route('admin.testimonial.index') }}">
                <i class="fa-solid fa-comments"></i> Testimonial
            </a>
        </li>

    </ul>
</div>