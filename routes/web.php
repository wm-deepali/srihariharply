<?php

use App\Http\Controllers\Admin\{
    DashboardController,
    ProfileSettingController,
    LogoController,
    SliderController,
    OurServiceController,
    IntroductionController,
    ClientController,
    AboutUsController,
    ProductCategoryController,
    BrandController,
    CategoryDetailController,
    GalleryController,
    GalleryDetailController,
    TestimonialController,
};

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontController;



Route::controller(FrontController::class)->group(function () {

    Route::get('/', 'home')->name('home');

});



// Admin Routes list
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');


Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['auth'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/profile-setting', ProfileSettingController::class);
        Route::post('/resetpassword', [ProfileSettingController::class, 'resetPassword'])->name('reset.password');

        // Logo Management (single view, no add — same as old logo.php)
        Route::resource('/logo', LogoController::class)->except(['show']);
        Route::post('/logo/{logo}/toggle-status', [LogoController::class, 'toggleStatus'])->name('logo.toggle-status');

        // Slider Management
        Route::resource('/slider', SliderController::class)->except(['show']);
        Route::post('/slider/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('slider.toggle-status');

        // Home Page Mgmt
        Route::resource('/our-services', OurServiceController::class)->except(['show']);
        Route::get('/introduction', [IntroductionController::class, 'index'])->name('introduction.index');
        Route::put('/introduction/{introduction}', [IntroductionController::class, 'update'])->name('introduction.update');
        Route::resource('/client', ClientController::class)->except(['show']);

        // About Us Page Mgmt (view/edit only — same as old about-us.php)
        Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us.index');
        Route::put('/about-us/{about_us}', [AboutUsController::class, 'update'])->name('about-us.update');

        // Product Category Mgmt
        Route::resource('/product-category', ProductCategoryController::class)->except(['show']);
        Route::resource('/brand', BrandController::class)->except(['show']);
        Route::resource('/category-details', CategoryDetailController::class)->except(['show']);

        // Picture Gallery
        Route::resource('/gallery', GalleryController::class)->except(['show']);
        Route::resource('/gallery-details', GalleryDetailController::class)->except(['show']);

        // Testimonial (view/list only — same as old contact.php)
        Route::get('/testimonial', [TestimonialController::class, 'index'])->name('testimonial.index');
        Route::delete('/testimonial/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonial.destroy');

    });
});