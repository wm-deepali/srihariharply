<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Services\Tracking\PixelTracker;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $quantity = $request->quantity ?? $product->min_qty;

        /*
        |--------------------------------------------------------------------------
        | Get Cart
        |--------------------------------------------------------------------------
        */

        if (auth('customer')->check()) {

            $customer = auth('customer')->user();

            $cart = Cart::firstOrCreate(
                ['user_id' => $customer->id],
                [
                    'session_id' => session()->getId(),
                    'total_amount' => 0,
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax_amount' => 0,
                    'grand_total' => 0,
                ]
            );

        } else {

            $cart = Cart::firstOrCreate(
                ['session_id' => session()->getId()],
                [
                    'total_amount' => 0,
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax_amount' => 0,
                    'grand_total' => 0,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Price & Stock
        |--------------------------------------------------------------------------
        */

        $price = $product->price;
        $stock = $product->stock;

        if ($stock < $product->min_qty) {

            return response()->json([
                'status' => false,
                'message' => 'Product is out of stock.'
            ], 422);
        }

        if ($quantity > $stock) {

            return response()->json([
                'status' => false,
                'message' => "Only {$stock} units available."
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Item
        |--------------------------------------------------------------------------
        */

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {

            $newQty = $item->quantity + $quantity;

            if ($newQty > $stock) {

                return response()->json([
                    'status' => false,
                    'message' => "Only {$stock} units available."
                ], 422);
            }

            $item->quantity = $newQty;
            $item->total = $item->quantity * $item->price;
            $item->save();

        } else {

            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $price * $quantity,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Recalculate Cart
        |--------------------------------------------------------------------------
        */

        $cart->recalculateTotals();
        $cart->refresh();

        // Adding items can only make a coupon's conditions easier to satisfy,
        // but a percentage discount amount still needs to track the new subtotal.
        $this->revalidateCoupon($cart);
        $cart->refresh();

        $trackingEvents = PixelTracker::addToCart($product, $quantity, $price);

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully.',
            'cart_count' => $cart->items()->sum('quantity'),
            'cart_total' => $cart->grand_total,
            'cart_item_id' => $item->id,
            'tracking_events' => $trackingEvents,
        ]);
    }

    public function cart()
    {
        if (auth('customer')->check()) {

            $cart = Cart::with([
                'items.product.images',
                'items.product.category',
                'items.product.subcategory',
            ])
                ->where('user_id', auth('customer')->id())
                ->first();

        } else {

            $cart = Cart::with([
                'items.product.images',
                'items.product.category',
                'items.product.subcategory',
            ])
                ->where('session_id', session()->getId())
                ->first();
        }

        // Recalculate GST based on latest default address
        if ($cart) {
            $cart->recalculateTotals();
            $cart->refresh();

            // Cart may have been touched from another tab/request since the coupon was applied
            $this->revalidateCoupon($cart);
            $cart->refresh();
        }

        /*
        |--------------------------------------------------------------------------
        | Nudge Message (Add X more items to unlock a quantity-based coupon)
        |--------------------------------------------------------------------------
        */

        $nudge = null;

        if ($cart && $cart->items->isNotEmpty()) {

            $subtotal = $cart->subtotal;
            $totalQuantity = $cart->items()->sum('quantity');

            $candidateCoupons = Coupon::where('status', 1)
                ->where('visibility', 'public')
                ->whereNotNull('minimum_order_quantity')
                ->where(function ($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderBy('minimum_order_quantity', 'asc')
                ->get();

            foreach ($candidateCoupons as $coupon) {

                $remainingQty = $coupon->minimum_order_quantity - $totalQuantity;

                $amountOk = !$coupon->minimum_order_amount || $subtotal >= $coupon->minimum_order_amount;

                // Show nudge only when customer is exactly 1 item away
                if ($remainingQty == 1 && $amountOk) {

                    $discountText = $coupon->discount_type === 'percentage'
                        ? $coupon->discount_value . '% flat discount'
                        : '₹' . number_format($coupon->discount_value, 0) . ' flat discount';

                    $nudge = [
                        'message' => "Add 1 more item and get {$discountText}!",
                        'code' => $coupon->code,
                    ];

                    break;
                }
            }
        }

        return view(
            'front-pages.cart',
            compact('cart', 'nudge')
        );
    }


    public function remove($id)
    {
        $item = CartItem::findOrFail($id);

        if (auth('customer')->check()) {
            if ($item->cart->user_id != auth('customer')->id()) {
                abort(403);
            }
        } else {
            if ($item->cart->session_id != session()->getId()) {
                abort(403);
            }
        }

        $cart = $item->cart;

        // capture before delete
        $trackingEvents = \App\Services\Tracking\PixelTracker::removeFromCart(
            $item->product,
            $item->quantity,
            $item->price
        );

        $item->delete();

        $cart->recalculateTotals();
        $cart->refresh();

        $couponResult = $this->revalidateCoupon($cart);

        if ($couponResult && $couponResult['removed']) {
            $cart->recalculateTotals();
            $cart->refresh();
        }

        return response()->json([
            'status' => true,
            'message' => 'Item removed successfully',
            'cart_total' => $cart->grand_total,
            'cart_subtotal' => $cart->subtotal,      // 👈 new
            'cart_tax' => $cart->tax_amount,          // 👈 new
            'cart_count' => $cart->items()->sum('quantity'),
            'tracking_events' => $trackingEvents,
            'coupon_removed' => $couponResult['removed'] ?? false,
            'coupon_message' => $couponResult['message'] ?? null,
            'discount' => $cart->discount,
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'action' => 'required|in:plus,minus',
        ]);

        $item = CartItem::findOrFail($request->item_id);

        if (auth('customer')->check()) {

            if ($item->cart->user_id != auth('customer')->id()) {
                abort(403);
            }

        } else {

            if ($item->cart->session_id != session()->getId()) {
                abort(403);
            }
        }

        $stock = $item->variant
            ? $item->variant->stock
            : $item->product->stock;

        $minQty = $item->product->min_qty;

        if ($request->action == 'plus') {

            if ($item->quantity + 1 > $stock) {

                return response()->json([
                    'status' => false,
                    'message' => 'Only ' . $stock . ' units available in stock.'
                ], 422);
            }

            $item->quantity++;

        } elseif ($request->action == 'minus') {

            if ($item->quantity - 1 >= $minQty) {
                $item->quantity--;
            }
        }

        $item->total = $item->quantity * $item->price;
        $item->save();

        $cart = $item->cart;

        $cart->recalculateTotals();
        $cart->refresh();

        $couponResult = $this->revalidateCoupon($cart);

        if ($couponResult && $couponResult['removed']) {
            $cart->recalculateTotals();
            $cart->refresh();
        }

        $mrp = $item->variant->mrp ?? $item->product->mrp;
        $totalMrp = $mrp * $item->quantity;

        return response()->json([
            'status' => true,
            'quantity' => $item->quantity,
            'item_total' => $item->total,
            'total_mrp' => $totalMrp,
            'cart_total' => $cart->grand_total,
            'cart_subtotal' => $cart->subtotal,      // 👈 new
            'cart_tax' => $cart->tax_amount,          // 👈 new
            'cart_count' => $cart->items()->sum('quantity'),
            'coupon_removed' => $couponResult['removed'] ?? false,
            'coupon_message' => $couponResult['message'] ?? null,
            'discount' => $cart->discount,
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required'
        ]);

        $cart = auth('customer')->check()
            ? Cart::where('user_id', auth('customer')->id())->first()
            : Cart::where('session_id', session()->getId())->first();

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart not found'
            ]);
        }

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('status', 1)
            ->first();

        if (!$coupon) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        if ($coupon->start_date && now()->lt($coupon->start_date)) {

            return response()->json([
                'status' => false,
                'message' => 'Coupon not started yet'
            ]);
        }

        if ($coupon->end_date && now()->gt($coupon->end_date)) {

            return response()->json([
                'status' => false,
                'message' => 'Coupon expired'
            ]);
        }

        if (
            $coupon->customer_type === 'new' &&
            auth('customer')->check()
        ) {

            $hasPreviousOrder = \App\Models\Order::where(
                'customer_id',
                auth('customer')->id()
            )->exists();

            if ($hasPreviousOrder) {

                return response()->json([
                    'status' => false,
                    'message' => 'This coupon is only valid for new customers.'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Usage Limit Per Customer
        |--------------------------------------------------------------------------
        */

        if (
            auth('customer')->check() &&
            $coupon->usage_limit
        ) {

            $usedCount = \App\Models\Order::where(
                'customer_id',
                auth('customer')->id()
            )
                ->where('coupon_id', $coupon->id)
                ->count();

            if ($usedCount >= $coupon->usage_limit) {

                return response()->json([
                    'status' => false,
                    'message' => 'You have already used this coupon the maximum allowed number of times.'
                ]);
            }
        }

        $subtotal = $cart->items()->sum('total');

        if (
            $coupon->minimum_order_amount &&
            $subtotal < $coupon->minimum_order_amount
        ) {

            return response()->json([
                'status' => false,
                'message' => 'Minimum order amount not reached'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Order Quantity Check
        |--------------------------------------------------------------------------
        */

        $totalQuantity = $cart->items()->sum('quantity');

        if (
            $coupon->minimum_order_quantity &&
            $totalQuantity < $coupon->minimum_order_quantity
        ) {

            $remainingQty = $coupon->minimum_order_quantity - $totalQuantity;

            return response()->json([
                'status' => false,
                'message' => "Add {$remainingQty} more item" . ($remainingQty > 1 ? 's' : '') . " to unlock this coupon (minimum {$coupon->minimum_order_quantity} items required)."
            ]);
        }

        if ($coupon->discount_type == 'percentage') {

            $discount =
                ($subtotal * $coupon->discount_value) / 100;

            if (
                $coupon->maximum_discount &&
                $discount > $coupon->maximum_discount
            ) {
                $discount = $coupon->maximum_discount;
            }

        } else {

            $discount = $coupon->discount_value;
        }

        $cart->update([
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'discount' => $discount
        ]);

        $cart->recalculateTotals();
        $cart->refresh();

        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully'
        ]);
    }

    public function removeCoupon()
    {
        $cart = auth('customer')->check()
            ? Cart::where('user_id', auth('customer')->id())->first()
            : Cart::where('session_id', session()->getId())->first();

        $cart->update([
            'coupon_id' => null,
            'coupon_code' => null,
            'discount' => 0
        ]);

        $cart->recalculateTotals();
        $cart->refresh();

        return response()->json([
            'status' => true
        ]);
    }


    public function availableCoupons()
    {
        $cart = auth('customer')->check()
            ? Cart::where('user_id', auth('customer')->id())->first()
            : Cart::where('session_id', session()->getId())->first();

        $subtotal = $cart ? $cart->subtotal : 0;
        $totalQuantity = $cart ? $cart->items()->sum('quantity') : 0;

        $coupons = Coupon::where('status', 1)
            ->where('visibility', 'public')
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->get()
            ->map(function ($coupon) use ($subtotal, $totalQuantity) {

                if ($coupon->discount_type === 'percentage') {
                    $desc = 'Get <strong>' . $coupon->discount_value . '% OFF</strong>';

                    if ($coupon->maximum_discount) {
                        $desc .= ' (up to ₹' . number_format($coupon->maximum_discount, 0) . ')';
                    }
                } else {
                    $desc = 'Get flat <strong>₹' . number_format($coupon->discount_value, 0) . ' OFF</strong>';
                }

                if ($coupon->minimum_order_amount) {
                    $desc .= ' on orders above ₹' . number_format($coupon->minimum_order_amount, 0) . '.';
                } else {
                    $desc .= ' on any order.';
                }

                if ($coupon->minimum_order_quantity) {
                    $desc .= ' Minimum ' . $coupon->minimum_order_quantity . ' items required.';
                }

                $eligible =
                    (!$coupon->minimum_order_amount || $subtotal >= $coupon->minimum_order_amount)
                    &&
                    (!$coupon->minimum_order_quantity || $totalQuantity >= $coupon->minimum_order_quantity);

                return [
                    'code' => $coupon->code,
                    'description' => $desc,
                    'eligible' => $eligible,
                    'min_amount' => $coupon->minimum_order_amount,
                    'min_quantity' => $coupon->minimum_order_quantity,
                    'current_quantity' => $totalQuantity,
                ];
            });

        return response()->json([
            'status' => true,
            'coupons' => $coupons,
        ]);
    }

    /**
     * Re-check an applied coupon's conditions against the cart's current
     * subtotal/quantity. Removes the coupon if it no longer qualifies,
     * and re-syncs a percentage discount's amount if the subtotal shifted.
     *
     * Call this after any operation that changes cart contents (add,
     * remove, quantity update) — right after recalculateTotals()/refresh().
     */
    private function revalidateCoupon(Cart $cart): ?array
    {
        if (!$cart->coupon_id) {
            return null;
        }

        $coupon = Coupon::find($cart->coupon_id);

        if (!$coupon || !$coupon->status) {
            $cart->update(['coupon_id' => null, 'coupon_code' => null, 'discount' => 0]);

            return [
                'removed' => true,
                'message' => 'Your applied coupon is no longer available and has been removed.',
            ];
        }

        $subtotal = $cart->subtotal;
        $totalQuantity = $cart->items()->sum('quantity');

        $amountOk = !$coupon->minimum_order_amount || $subtotal >= $coupon->minimum_order_amount;
        $qtyOk = !$coupon->minimum_order_quantity || $totalQuantity >= $coupon->minimum_order_quantity;

        if (!$amountOk || !$qtyOk) {
            $cart->update(['coupon_id' => null, 'coupon_code' => null, 'discount' => 0]);

            return [
                'removed' => true,
                'message' => "Coupon \"{$coupon->code}\" was removed — your cart no longer meets its minimum requirement.",
            ];
        }

        // Cart still qualifies — but a percentage discount amount needs to
        // track the new subtotal (flat discounts don't change).
        if ($coupon->discount_type === 'percentage') {
            $discount = ($subtotal * $coupon->discount_value) / 100;

            if ($coupon->maximum_discount && $discount > $coupon->maximum_discount) {
                $discount = $coupon->maximum_discount;
            }

            if ((float) $discount !== (float) $cart->discount) {
                $cart->update(['discount' => $discount]);
            }
        }

        return ['removed' => false];
    }

}