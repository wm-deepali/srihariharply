<?php

namespace App\Providers;
use App\Models\AboutUs;
use App\Models\Logo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        View::composer('layouts.front', function ($view) {
            $view->with([
                'siteLogo' => Logo::where('status', 'active')->first(),
                'footerAbout' => AboutUs::where('status', 'active')->first(),
            ]);
        });
    }
}