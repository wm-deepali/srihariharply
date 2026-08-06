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

        {{-- MASTER --}}
        <li class="{{ request()->routeIs(
    'admin.categories.*',
    // 'admin.attributes.*',
    // 'admin.attribute-values.*',
    // 'admin.category-attributes.*',
    // 'admin.gifting-occasions.*',
    'admin.collections.*',
) ? 'active' : '' }}">

            <a href="#"><i class="fa-solid fa-layer-group"></i> Master</a>
            <ul>
                <li><a href="{{ route('admin.categories.index') }}">Categories & Sub Categories</a></li>
                <!-- <li><a href="{{ route('admin.attributes.index') }}">Attributes</a></li> -->
                <!-- <li><a href="{{ route('admin.attribute-values.index') }}">Attribute Values</a></li> -->
                <!-- <li><a href="{{ route('admin.category-attributes.index') }}">Attribute Mapping</a></li> -->
                <!-- <li><a href="{{ route('admin.gifting-occasions.index') }}">Manage Occasions</a></li> -->
                <li><a href="{{ route('admin.collections.index') }}">Manage Collections</a></li>
            </ul>
        </li>

        {{-- PRODUCT & INVENTORIES --}}
        <li class="{{ request()->routeIs(
    'admin.products.*',
    'admin.product-reviews.*',
    'admin.stock.*',
    'admin.stock-alerts.*'
) ? 'active' : '' }}">
            <a href="#"><i class="fa-solid fa-box"></i> Product & Inventories</a>
            <ul>
                <li><a href="{{ route('admin.products.index') }}">Manage Products</a></li>
                <!-- <li><a href="{{ route('admin.reviews.index') }}">Product Reviews</a></li> -->
                <li><a href="{{ route('admin.stock.index') }}">Stock Management</a></li>
                <li><a href="{{ route('admin.stock.alerts') }}">Stock Alerts</a></li>
            </ul>
        </li>

        {{-- CUSTOMER & ORDERS --}}
        <li class="{{ request()->routeIs(
    'admin.orders.*',
    'admin.transactions.*',
    'admin.returns.*',
    'admin.refunds.*',
    'admin.customers.*'
) ? 'active' : '' }}">
            <a href="#"><i class="fa-solid fa-cart-shopping"></i> Customer & Orders</a>
            <ul>
                <li><a href="{{ route('admin.orders.index')}}">Manage Orders</a></li>
                 <li><a href="{{ route('admin.orders.print-labels') }}">Print Label</a></li>
                <li><a href="{{ route('admin.payments.index')}}">Payments & Transactions</a></li>
                <!-- <li><a href="{{ route('admin.order-returns.index')}}">Manage Returns</a></li> -->
                <!-- <li><a href="{{ route('admin.return-reasons.index') }}">Return Reasons</a></li> -->
                <!-- <li><a href="{{route('admin.refunds.index') }}">Manage Refunds</a></li> -->
                <li><a href="{{ route('admin.customers.index') }}">Manage Customers</a></li>
                <li><a href="{{ route('admin.customers.addresses.index') }}">Customer Address Book</a></li>
                <li><a href="{{ route('admin.stored-carts.index') }}">Customer Carts</a></li>

            </ul>
        </li>

        {{-- CONTENT MANAGEMENT --}}
        <li class="{{ request()->routeIs(
    'admin.home.*',
    'admin.banners.*',
    'admin.faqs.*',
    'admin.blogs.*',
    'admin.pages.*',
    'admin.announcements.*',
    'admin.clients.*',
    'admin.testimonials.*',
    'admin.teams.*'
) ? 'active' : '' }}">
            <a href="#"><i class="fa-solid fa-file-lines"></i> Content Management</a>
            <ul>
                <li><a href="{{ route('admin.home.index') }}">Home Page Widgets</a></li>
                <!-- <li><a href="#">Banners & Sliders</a></li> -->
                <li><a href="{{ route('admin.faqs.index') }}">FAQ</a></li>
                <li><a href="{{ route('admin.blogs.index') }}">Blog Management</a></li>
                <li><a href="{{ route('admin.pages.index') }}">Dynamic Pages</a></li>
                <li><a href="{{ route('admin.announcements.index') }}">Announcement Bar</a></li>
            </ul>
        </li>

        {{-- ENQUIRIES --}}
        <li class="{{ request()->routeIs(
    'admin.contact-enquiries.*',
    'admin.other-enquiries.*',
    'admin.supplier-enquiries.*'
) ? 'active' : '' }}">
            <a href="#"><i class="fa-solid fa-envelope"></i> Enquiries</a>
            <ul>
                <li><a href="{{ route('admin.contact-enquiries.index') }}">Contact Us Enquiries</a></li>
                <!-- <li><a href="{{ route('admin.supplier-enquiries.index') }}">Bulk Order Enquiries</a></li> -->
                <!-- <li><a href="{{ route('admin.other-enquiries.index') }}">Other Enquiries</a></li> -->
            </ul>
        </li>

        {{-- MARKETING & SETTINGS --}}
        <li class="{{ request()->routeIs(
    'admin.coupons.*',
    'admin.seo.*',
    'admin.footer-settings.*'
) ? 'active' : '' }}">
            <a href="#"><i class="fa-solid fa-gear"></i> Marketing & Settings</a>
            <ul>


                <li>
                    <a href="{{ route('admin.coupons.index') }}">
                        Coupon Management
                    </a>
                </li>
                <li><a href="{{ route('admin.seo.index') }}">SEO Management</a></li>


            </ul>
        </li>


       {{-- Admin Setting --}}
<li class="{{ request()->routeIs(
    'admin.settings.*',
) ? 'active' : '' }}">
    <a href="#"><i class="fa-solid fa-gear"></i> Settings</a>
    <ul>
        <li><a href="{{ route('admin.admin-setting.index', ['tab' => 'general']) }}">General Setting</a></li>
        <li><a href="{{ route('admin.admin-setting.index', ['tab' => 'payment']) }}">Payment Gateway</a></li>
        <li><a href="{{ route('admin.admin-setting.index', ['tab' => 'smtp']) }}">SMTP</a></li>
        <li>
            <a href="{{ route('admin.admin-setting.index', ['tab' => 'gst']) }}">
                GST & Invoice
            </a>
        </li>
        <li>
            <a href="{{ route('admin.admin-setting.index', ['tab' => 'couriers']) }}">
                Courier Management
            </a>
        </li>
        <li>
            <a href="{{ route('admin.admin-setting.index', ['tab' => 'sms']) }}">
                SMS
            </a>
        </li>
        <li>
            <a href="{{ route('admin.admin-setting.index', ['tab' => 'tracking']) }}">
                Tracking & Pixels
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.templates.index') }}">
                Template Settings
            </a>
        </li>
    </ul>
</li>




        {{-- REPORTS --}}
        <li class="{{ request()->routeIs(
    'admin.sales-reports.*',
    'admin.product-reports.*',
    'admin.customer-reports.*'
) ? 'active' : '' }}">
            <a href="#"><i class="fa-solid fa-chart-column"></i> Reports</a>
            <ul>
                <li><a href="{{ route('admin.reports.sales') }}">Sales Report</a></li>
                <li><a href="{{ route('admin.reports.products') }}">Product Report</a></li>
                <li><a href="{{ route('admin.reports.customers') }}">Customer Report</a></li>
            </ul>
        </li>


    </ul>
</div>