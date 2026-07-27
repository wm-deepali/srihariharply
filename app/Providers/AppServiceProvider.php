<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use Illuminate\Support\Facades\View;
use App\Models\DynamicPage;
use App\Models\Collection;
use App\Models\Category;
use App\Models\GoogleSetting; // 👈 new

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
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::guard('customer')->check()) {
                $cart = Cart::with('items')
                    ->where('user_id', Auth::guard('customer')->id())
                    ->first();
                $wishlistItems = \App\Models\Wishlist::where(
                    'customer_id',
                    Auth::guard('customer')->id()
                )->pluck('product_id');
            } else {
                $cart = Cart::with('items')
                    ->where('session_id', session()->getId())
                    ->first();
                $wishlistItems = \App\Models\Wishlist::where(
                    'session_id',
                    session()->getId()
                )->pluck('product_id');
            }

            $count = $cart
                ? $cart->items->sum('quantity')
                : 0;

            $wishlistCount = $wishlistItems->count();

            $headerCategories = Category::with('children')
                ->whereNull('parent_id')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->get();

            $collections = Collection::where('show_in_navigation', 1)
                ->where('status', 1)
                ->orderBy('sort_order')
                ->get();

            $pages = DynamicPage::where('status', 1)->get();

            $general = \App\Models\Setting::first();

            $googleSetting = GoogleSetting::current(); // 👈 new

            $view->with(
                [
                    'globalCartCount' => $count,
                    'headerCollections' => $collections,
                    'headerCategories' => $headerCategories,
                    'general' => $general,
                    'footerPages' => $pages,
                    'wishlistCount' => $wishlistCount,
                    'wishlistProductIds' => $wishlistItems->toArray(),
                    'googleSetting' => $googleSetting, // 👈 new
                ]
            );
        });
    }
}