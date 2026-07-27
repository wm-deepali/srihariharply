<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $customer = auth('customer')->user();
        $wishlists = Wishlist::current()
            ->with([
                'product.category',
                'product.subcategory',
                'product.images',
            ])
            ->latest()
            ->get();

        return view('user.wishlist', compact('wishlists', 'customer'));
    }

    /*
    |--------------------------------------------------------------------------
    | Add to Wishlist
    |--------------------------------------------------------------------------
    */

  public function add(Request $request)
{
    $settings = Setting::first();

    if (!$settings || !$settings->wishlist) {
        return response()->json([
            'status' => false,
            'message' => 'Wishlist feature is disabled.',
        ]);
    }

    $product = Product::findOrFail($request->product_id);

    $exists = Wishlist::current()
        ->where('product_id', $product->id)
        ->exists();

    if ($exists) {
        return response()->json([
            'status' => false,
            'message' => 'Product is already in your wishlist.',
            'wishlist_count' => Wishlist::current()->count(),
        ]);
    }

    Wishlist::addProduct($product);

    $trackingEvents = \App\Services\Tracking\PixelTracker::addToWishlist($product); // 👈 new

    return response()->json([
        'status' => true,
        'message' => 'Product added to wishlist successfully.',
        'wishlist_count' => Wishlist::current()->count(),
        'tracking_events' => $trackingEvents, // 👈 new
    ]);
}


    /*
    |--------------------------------------------------------------------------
    | Remove from Wishlist
    |--------------------------------------------------------------------------
    */

    public function remove(Request $request, Product $product)
    {
        Wishlist::removeProduct($product);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist.',
                'wishlist_count' => Wishlist::current()->count(),
            ]);
        }

        return back()->with('success', 'Item removed from your wishlist.');
    }


    /*
    |--------------------------------------------------------------------------
    | Move to Cart
    |--------------------------------------------------------------------------
    */

    public function moveToCart(Request $request, Product $product)
    {
        // Get or create cart
        $cart = \App\Models\Cart::firstOrCreate(
            auth('customer')->check()
            ? [
                'user_id' => auth('customer')->id(),
            ]
            : [
                'session_id' => session()->getId(),
            ]
        );

        // Get or create cart item
        $cartItem = CartItem::firstOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
            ],
            [
                'quantity' => 1,
                'price' => $product->price,
                'total' => $product->price,

            ]
        );

        // Increase quantity if already exists
        if (!$cartItem->wasRecentlyCreated) {
            $cartItem->increment('quantity');
        }

        // Remove from wishlist
        Wishlist::removeProduct($product);

        // Recalculate cart totals (if your Cart model has this method)
        if (method_exists($cart, 'recalculateTotals')) {
            $cart->recalculateTotals();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} moved to cart.",
                'cart_count' => $cart->items()->sum('quantity'),
                'wishlist_count' => Wishlist::current()->count(),
            ]);
        }

        return back()->with('success', "{$product->name} moved to your cart.");
    }

}