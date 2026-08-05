<?php

use App\Http\Controllers\Admin\{
    AdminSettingController,
    AnnouncementBarController,
    AttributeController,
    AttributeValueController,
    AwardController,
    BlogController,
    CategoryAttributeController,
    CategoryController,
    ClientController,
    CollectionController,
    ContactBranchController,
    ContactEnquiryController,
    CouponController,
    CustomerAddressController,
    CustomerController,
    CustomizationController,
    DashboardController,
    DynamicPageController,
    FaqController,
    GiftingOccasionController,
    HomePageController,
    LogoutController,
    OrderController,
    OtherEnquiryController,
    PaymentController,
    ProductController,
    ProfileSettingController,
    ReturnReasonController,
    SeoController,
    StoredCartController,
    SupplierEnquiryController,
    TeamController,
    TestimonialController,
    VendorTypeController,
    OrderReturnController,
    RefundController,
    StockManagementController,
    StockAlertsController,
    SalesReportController,
    ProductReportController,
    CustomerReportController,
    HeroSectionController,
    BannerSectionController,
    TestimonialSectionController,
    AudioSectionController,
    SmsSettingsController,
    SmsTemplateController,
    EmailTemplateController
};

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Frontend\Auth\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\ProductReviewController;


Route::middleware('maintenance.mode')->group(function () {


    Route::controller(FrontController::class)->group(function () {

        Route::get('/', 'home')->name('home');
        Route::get('/search-suggestions', 'searchSuggestions')->name('search.suggestions');

        Route::get('/category/{category:slug}', 'category')->name('shop.category');
        Route::get('/collection/{collection:slug}', 'collection')->name('shop.collection');
        Route::get('/shop', 'shopAll')->name('shop.all');

        Route::get('/product/{product:slug}', 'productDetail')->name('shop.product');

        Route::get('/about-us', 'aboutUs')->name('about-us');
        Route::get('/blogs', 'blogs')->name('blogs');
        Route::get('/blog/{slug}', 'blogDetails')->name('blog.details');
        Route::get('/bulk-enquiry', 'bulkEnquiry')->name('bulk-enquiry');
        Route::get('/contact-us', 'contactUs')->name('contact-us');
        Route::get('/faqs', 'faqs')->name('faqs');
        Route::get('/page/{slug}', 'dynamicPage')->name('dynamic.page');
        Route::get('/why-us', 'whyUs')->name('why-us');

        Route::post('/contact-submit', 'submitContact')->name('contact.submit');
        Route::post('/supplier-enquiry-submit', 'submitSupplierEnquiry')->name('supplier.enquiry.submit');
        Route::post('/general-enquiry', 'submitGeneralEnquiry')->name('general.enquiry');

        Route::prefix('wishlist')->name('wishlist.')->group(function () {

            Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('add');
            Route::delete('/{product}', [WishlistController::class, 'remove'])->name('remove');
            Route::post('/{product}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('moveToCart');

        });

        // cart routes
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::get('/cart', [CartController::class, 'cart'])->name('cart');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
        Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
        Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
        Route::get('/cart/available-coupons', [CartController::class, 'availableCoupons'])->name('cart.available-coupons');

        Route::middleware('customer')->group(function () {

            Route::get('/checkout', [CheckoutController::class, 'checkout'])
                ->name('checkout');

            Route::post('/address/store', [CheckoutController::class, 'storeAddress'])->name('address.store');

            Route::post('/checkout/change-default-address', [CheckoutController::class, 'changeDefaultAddress'])
                ->name('checkout.change-default-address');

            Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])
                ->name('checkout.place-order');

            Route::post('/payment/razorpay/success', [CheckoutController::class, 'razorpaySuccess'])
                ->name('checkout.razorpay.success');

            Route::get('/order-success/{order}', [CheckoutController::class, 'orderSuccess'])
                ->name('order.success');

        });


    });

    // Google OAuth
    Route::get('/auth/google', [CustomerAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [CustomerAuthController::class, 'handleGoogleCallback']);


    Route::prefix('user')->name('user.')->group(function () {

        Route::get('/login', [CustomerAuthController::class, 'loginForm'])->name('login');
        Route::get('/register', [CustomerAuthController::class, 'registerForm'])->name('register');
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

        // Register flow
        Route::post('/send-otp', [CustomerAuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [CustomerAuthController::class, 'verifyOtp']);
        Route::post('/register', [CustomerAuthController::class, 'register']);
        Route::post('/guest-login', [CustomerAuthController::class, 'guestLogin']);


        // Login: mobile OTP path
        Route::post('/send-login-otp', [CustomerAuthController::class, 'sendLoginOtp']);
        Route::post('/verify-login-otp', [CustomerAuthController::class, 'verifyLoginOtp']);

        // Login: email + password path (JSON response, used by fetch)
        Route::post('/login-email', [CustomerAuthController::class, 'loginEmail']);

        // Register: trust mobile coming from login page (no-OTP bridge)
        Route::post('/trust-login-mobile', [CustomerAuthController::class, 'trustLoginMobile']);
        Route::post('/trust-login-mobile-check', [CustomerAuthController::class, 'trustLoginMobileCheck']);

        // Forgot password flow
        Route::post('/send-password-reset-otp', [CustomerAuthController::class, 'sendPasswordResetOtp']);
        Route::post('/verify-password-reset-otp', [CustomerAuthController::class, 'verifyPasswordResetOtp']);
        Route::post('/reset-password', [CustomerAuthController::class, 'resetPassword']);

        Route::middleware('customer')->group(function () {

            Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
            Route::put('/profile', [AccountController::class, 'update'])->name('profile.update');


            Route::get('orders', [App\Http\Controllers\User\OrderController::class, 'index'])->name('orders');
            Route::get('orders/{order}', [App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
            Route::get('orders/{order}/invoice', [App\Http\Controllers\User\OrderController::class, 'invoice'])->name('orders.invoice');
            Route::post('orders/return', [App\Http\Controllers\User\OrderController::class, 'submitReturn'])->name('orders.return');
            Route::get('orders/{order}/reorder', [App\Http\Controllers\User\OrderController::class, 'reorder'])->name('orders.reorder');

            // Addresses
            Route::get('/addresses', [App\Http\Controllers\User\AddressController::class, 'index'])->name('addresses.index');
            Route::post('/addresses', [App\Http\Controllers\User\AddressController::class, 'store'])->name('addresses.store');
            Route::get('/addresses/{id}/edit', [App\Http\Controllers\User\AddressController::class, 'edit'])->name('addresses.edit');
            Route::put('/addresses/{id}', [App\Http\Controllers\User\AddressController::class, 'update'])->name('addresses.update');
            Route::delete('/addresses/{id}', [App\Http\Controllers\User\AddressController::class, 'destroy'])->name('addresses.destroy');
            Route::patch('/addresses/{id}/default', [App\Http\Controllers\User\AddressController::class, 'setDefault'])->name('addresses.default');
            Route::get('/addresses/cities', [App\Http\Controllers\User\AddressController::class, 'cities'])->name('addresses.cities');

            Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

            Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
            Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
            Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

            Route::post('/reviews/store', [ProductReviewController::class, 'store'])->name('reviews.store');
            Route::delete('/reviews/{review}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');

            Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

        });

    });


});



// Admin Routes list
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/get-cities', [AdminSettingController::class, 'getCities'])->name('get-cities');



Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['auth', 'admin.timeout'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/profile-setting', ProfileSettingController::class);
        Route::post('/resetpassword', [ProfileSettingController::class, 'resetPassword'])->name('reset.password');

        // category routes
        Route::get('categories/import', [CategoryController::class, 'import'])->name('categories.import');
        Route::post('categories/import', [CategoryController::class, 'importStore'])->name('categories.import.store');
        Route::get('categories/import/sample', [CategoryController::class, 'downloadSample'])->name('categories.import.sample');
        Route::post('categories/upload-images', [CategoryController::class, 'uploadImagesZip'])->name('categories.images.upload');
        Route::get('categories/import/parent-reference', [CategoryController::class, 'downloadParentCategoryReference'])->name('categories.parent.reference');
        Route::resource('categories', CategoryController::class);

        // occasion routes
        Route::get('gifting-occasions/import', [GiftingOccasionController::class, 'import'])->name('gifting-occasions.import');
        Route::post('gifting-occasions/import', [GiftingOccasionController::class, 'importStore'])->name('gifting-occasions.import.store');
        Route::get('gifting-occasions/import/sample', [GiftingOccasionController::class, 'downloadSample'])->name('gifting-occasions.import.sample');
        Route::post('gifting-occasions/upload-images', [GiftingOccasionController::class, 'uploadImagesZip'])->name('gifting-occasions.images.upload');
        Route::resource('gifting-occasions', GiftingOccasionController::class);

        // product routes
        Route::get('products/subcategories/{category}', [ProductController::class, 'subcategories'])->name('products.subcategories');
        Route::get('products/category-attributes/{category}', [ProductController::class, 'categoryAttributes'])->name('products.category-attributes');
        Route::resource('products', ProductController::class)->names('products');

        Route::resource('customizations', CustomizationController::class);

        Route::resource('pages', DynamicPageController::class)->names('pages');

        Route::resource('faqs', FaqController::class)->names('faqs');

        Route::resource('blogs', BlogController::class)->names('blogs');
        Route::post('/ckeditor/upload', [BlogController::class, 'ckeditorUpload'])->name('ckeditor.upload');

        Route::resource('clients', ClientController::class)->names('clients');

        Route::resource('testimonials', TestimonialController::class)->names('testimonials');

        Route::resource('contact-branches', ContactBranchController::class);

        Route::get('contact-enquiries/export', [ContactEnquiryController::class, 'export'])->name('contact-enquiries.export');
        Route::delete('contact-enquiries/bulk-delete', [ContactEnquiryController::class, 'bulkDelete'])->name('contact-enquiries.bulk-delete');
        Route::resource('contact-enquiries', ContactEnquiryController::class);

        Route::get('other-enquiries/export', [OtherEnquiryController::class, 'export'])->name('other-enquiries.export');
        Route::delete('other-enquiries/bulk-delete', [OtherEnquiryController::class, 'bulkDelete'])->name('other-enquiries.bulk-delete');
        Route::resource('other-enquiries', OtherEnquiryController::class);

        Route::get('supplier-enquiries/export', [SupplierEnquiryController::class, 'export'])->name('supplier-enquiries.export');
        Route::delete('supplier-enquiries/bulk-delete', [SupplierEnquiryController::class, 'bulkDelete'])->name('supplier-enquiries.bulk-delete');
        Route::resource('supplier-enquiries', SupplierEnquiryController::class);

        Route::resource('awards', AwardController::class);

        Route::resource('teams', TeamController::class);

        Route::resource('vendor-types', VendorTypeController::class);

        Route::get('/logout', [LogoutController::class, 'logout']);


        // ✅ MAIN DASHBOARD
        Route::get('/home', [HomePageController::class, 'index'])
            ->name('home.index');

        // 1. Hero Section
        Route::get('home/hero', [HeroSectionController::class, 'edit'])->name('home.hero.edit');
        Route::put('home/hero', [HeroSectionController::class, 'update'])->name('home.hero.update');

        // 2. Static Banner 1
        Route::get('home/banner1', [BannerSectionController::class, 'edit'])->name('home.banner1.edit');
        Route::put('home/banner1', [BannerSectionController::class, 'update'])->name('home.banner1.update');

        // 3. Testimonial Banner
        Route::get('home/testimonial', [TestimonialSectionController::class, 'edit'])->name('home.testimonial.edit');
        Route::put('home/testimonial', [TestimonialSectionController::class, 'update'])->name('home.testimonial.update');

        // 4. Audio Section
        Route::get('home/audio', [AudioSectionController::class, 'edit'])->name('home.audio.edit');
        Route::put('home/audio', [AudioSectionController::class, 'update'])->name('home.audio.update');


        Route::get('/seo', [SeoController::class, 'index'])->name('seo.index');
        Route::put('/seo/{id}', [SeoController::class, 'update'])->name('seo.update');

        Route::resource('collections', CollectionController::class);

        Route::resource('attributes', AttributeController::class);
        Route::resource('attribute-values', AttributeValueController::class);

        Route::resource('category-attributes', CategoryAttributeController::class);

        Route::get('settings/announcement-bar', [AnnouncementBarController::class, 'edit'])->name('announcements.edit');

        Route::post('settings/announcement-bar', [AnnouncementBarController::class, 'update'])->name('announcements.update');


        Route::post('coupons/{coupon}/share', [CouponController::class, 'share'])->name('coupons.share');
        Route::resource('coupons', CouponController::class);


        // Admin Settings routes
        Route::get('/admin-setting', [AdminSettingController::class, 'index'])->name('admin-setting.index');
        Route::post('/invoice-settings', [AdminSettingController::class, 'invoiceSettingStore'])->name('invoice-settings.store');
        Route::post('/smtp-settings/store', [AdminSettingController::class, 'smtpSettingStore'])->name('smtp-settings.store');
        Route::post('/payment-settings/store', [AdminSettingController::class, 'paymentSettingStore'])->name('payment-settings.store');
        Route::post('/settings/general', [AdminSettingController::class, 'generalSettingStore'])->name('settings.general.store');
        Route::post('/settings/courier/store', [AdminSettingController::class, 'courierStore'])->name('couriers.store');
        Route::delete('/settings/courier/{courier}', [AdminSettingController::class, 'courierDelete'])->name('couriers.delete');

        Route::post('admin-setting/google-setting', [AdminSettingController::class, 'googleSettingStore'])
            ->name('admin-setting.google-setting');

        Route::post('/settings/sms', [SmsSettingsController::class, 'update'])->name('settings.sms.update');
        Route::post('/settings/sms/test', [SmsSettingsController::class, 'test'])->name('settings.sms.test');

        Route::get('/settings/templates', [SmsTemplateController::class, 'index'])->name('settings.templates.index');
        Route::post('/settings/templates/send-test', [SmsTemplateController::class, 'test'])->name('settings.templates.test');
        Route::post('/settings/templates/{eventKey}', [SmsTemplateController::class, 'update'])->name('settings.templates.update');


        Route::post('settings/email-templates/test', [EmailTemplateController::class, 'sendTest'])->name('settings.email-templates.test');
        Route::post('settings/email-templates/{eventKey}', [EmailTemplateController::class, 'save'])->name('settings.email-templates.save');


        // Orders routes
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('orders/{order}/invoice/download', [OrderController::class, 'invoiceDownload'])->name('orders.invoice.download');

        // Payments routes
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');

        // Customer Address Book
        Route::get('customers/addresses', [CustomerAddressController::class, 'index'])->name('customers.addresses.index');
        Route::delete('customers/addresses/{address}', [CustomerAddressController::class, 'destroy'])->name('customers.addresses.destroy');
        Route::get('customers/addresses/export', [CustomerAddressController::class, 'export'])->name('customers.addresses.export');

        // Customers routes
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');

        Route::get('stored-carts', [StoredCartController::class, 'index'])->name('stored-carts.index');
        Route::delete('stored-carts/{cart}', [StoredCartController::class, 'destroy'])->name('stored-carts.destroy');
        Route::get('stored-carts/export', [StoredCartController::class, 'export'])->name('stored-carts.export');

        Route::resource('return-reasons', ReturnReasonController::class)->names('return-reasons');


        Route::get('order-returns', [OrderReturnController::class, 'index'])->name('order-returns.index');
        Route::get('order-returns/export', [OrderReturnController::class, 'export'])->name('order-returns.export');
        Route::get('order-returns/{orderReturn}', [OrderReturnController::class, 'show'])->name('order-returns.show');
        Route::patch('order-returns/{orderReturn}/approve', [OrderReturnController::class, 'approve'])->name('order-returns.approve');
        Route::patch('order-returns/{orderReturn}/reject', [OrderReturnController::class, 'reject'])->name('order-returns.reject');
        Route::post('order-returns/{orderReturn}/refund', [OrderReturnController::class, 'refund'])->name('order-returns.refund');

        Route::get('refunds', [RefundController::class, 'index'])->name('refunds.index');
        Route::get('refunds/export', [RefundController::class, 'export'])->name('refunds.export');


        Route::get('stock', [StockManagementController::class, 'index'])->name('stock.index');
        Route::get('stock/export', [StockManagementController::class, 'export'])->name('stock.export');
        Route::post('stock/bulk-update', [StockManagementController::class, 'bulkUpdate'])->name('stock.bulk-update');
        Route::post('stock/add-entry', [StockManagementController::class, 'addStockEntry'])->name('stock.add-entry');
        Route::post('stock/{product}/update', [StockManagementController::class, 'updateStock'])->name('stock.update');
        Route::post('stock/{product}/restock', [StockManagementController::class, 'restock'])->name('stock.restock');
        Route::get('stock/{product}/history', [StockManagementController::class, 'history'])->name('stock.history');
        Route::get('stock/bulk-update/template', [StockManagementController::class, 'downloadTemplate'])->name('stock.bulk-update.template');


        Route::get('stock/alerts', [StockAlertsController::class, 'index'])->name('stock.alerts');
        Route::post('stock/alerts/{product}/restock', [StockAlertsController::class, 'restock'])->name('stock.alerts.restock');
        Route::post('stock/alerts/settings/thresholds', [StockAlertsController::class, 'updateThresholds'])->name('stock.alerts.thresholds');
        Route::post('stock/alerts/settings/notifications', [StockAlertsController::class, 'updateNotifications'])->name('stock.alerts.notifications');
        Route::get('stock/alerts/export', [StockAlertsController::class, 'export'])->name('stock.alerts.export');
        Route::post('stock/alerts/restock-all-critical', [StockAlertsController::class, 'restockAllCritical'])->name('stock.alerts.restock.all');


        // Listing page
        Route::get('/reviews', [App\Http\Controllers\Admin\ProductReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{review}', [App\Http\Controllers\Admin\ProductReviewController::class, 'show'])->name('reviews.show');
        Route::patch('/reviews/{review}/approve', [App\Http\Controllers\Admin\ProductReviewController::class, 'approve'])->name('reviews.approve');
        Route::patch('/reviews/{review}/reject', [App\Http\Controllers\Admin\ProductReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ProductReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('/reviews/export/csv', [App\Http\Controllers\Admin\ProductReviewController::class, 'export'])->name('reviews.export');

        Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');
        Route::get('/reports/sales/export', [SalesReportController::class, 'export'])->name('reports.sales.export');

        Route::get('/reports/products', [ProductReportController::class, 'index'])->name('reports.products');
        Route::get('reports/products/export/csv', [ProductReportController::class, 'exportCsv'])->name('reports.products.export.csv');
        Route::get('reports/products/export/pdf', [ProductReportController::class, 'exportPdf'])->name('reports.products.export.pdf');

        Route::get('reports/customers', [CustomerReportController::class, 'index'])->name('reports.customers');
        Route::get('reports/customers/export/excel', [CustomerReportController::class, 'exportExcel'])->name('reports.customers.export.excel');
        Route::get('reports/customers/export/pdf', [CustomerReportController::class, 'exportPdf'])->name('reports.customers.export.pdf');
        // Cleaner URL
        Route::get('/notifications/', function () {
            return view('admin.notifications.index');
        })->name('notifications.index');



        Route::prefix('roles-and-permission')->group(function () {

            // Roles Category
            Route::view('/roles-category', 'admin.roles-and-permission.roles-category.index')->name('roles-category.index');
            Route::view('/roles-category/create', 'admin.roles-and-permission.roles-category.create')->name('roles-category.create');
            Route::view('/roles-category/edit', 'admin.roles-and-permission.roles-category.edit')->name('roles-category.edit');

            // Permission and Settings
            Route::view('/permission-and-settings', 'admin.roles-and-permission.permission-and-settings.index')->name('permission-settings.index');
            Route::view('/permission-and-settings/create', 'admin.roles-and-permission.permission-and-settings.create')->name('permission-settings.create');
            Route::view('/permission-and-settings/edit', 'admin.roles-and-permission.permission-and-settings.edit')->name('permission-settings.edit');

            // Team
            Route::view('/team', 'admin.roles-and-permission.team.index')->name('team.index');
            Route::view('/team/create', 'admin.roles-and-permission.team.create')->name('team.create');
            Route::view('/team/edit', 'admin.roles-and-permission.team.edit')->name('team.edit');
            Route::view('/team/customise-permission', 'admin.roles-and-permission.team.customise-permission')->name('team.customise-permission');
            Route::view('/team/activity-logs', 'admin.roles-and-permission.team.activity-logs')->name('team.activity-logs');
            Route::view('/team/login', 'admin.roles-and-permission.team.login')->name('team.login');
            Route::view('/team/login-history', 'admin.roles-and-permission.team.login-history')->name('team.login-history');

        });

    });
});
