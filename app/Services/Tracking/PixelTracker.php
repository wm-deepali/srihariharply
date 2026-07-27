<?php

namespace App\Services\Tracking;

use App\Models\GoogleSetting;
use App\Models\Order;

class PixelTracker
{
    /**
     * Build tracking payload for add_to_cart / AddToCart.
     * Returned array is sent inside AJAX JSON responses;
     * frontend JS fires the actual fbq/gtag calls.
     */
    public static function addToCart($product, int $quantity, float $price): ?array
    {
        $settings = GoogleSetting::current();

        $events = [];

        if ($settings->ga4_enabled && $settings->ga4_ev_add_to_cart) {
            $events['ga4'] = [
                'name' => 'add_to_cart',
                'params' => [
                    'currency' => 'INR',
                    'value' => $price * $quantity,
                    'items' => [[
                        'item_id' => (string) $product->id,
                        'item_name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                    ]],
                ],
            ];
        }

        if ($settings->meta_enabled && $settings->meta_ev_add_to_cart) {
            $events['meta'] = [
                'name' => 'AddToCart',
                'params' => [
                    'content_ids' => [(string) $product->id],
                    'content_name' => $product->name,
                    'content_type' => 'product',
                    'value' => $price * $quantity,
                    'currency' => 'INR',
                ],
            ];
        }

        return empty($events) ? null : $events;
    }

    public static function removeFromCart($product, int $quantity, float $price): ?array
    {
        $settings = GoogleSetting::current();

        $events = [];

        if ($settings->ga4_enabled && $settings->ga4_ev_remove_from_cart) {
            $events['ga4'] = [
                'name' => 'remove_from_cart',
                'params' => [
                    'currency' => 'INR',
                    'value' => $price * $quantity,
                    'items' => [[
                        'item_id' => (string) $product->id,
                        'item_name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                    ]],
                ],
            ];
        }

        return empty($events) ? null : $events;
    }

    public static function addToWishlist($product): ?array
    {
        $settings = GoogleSetting::current();

        if (!($settings->meta_enabled && $settings->meta_ev_add_to_wishlist)) {
            return null;
        }

        return [
            'meta' => [
                'name' => 'AddToWishlist',
                'params' => [
                    'content_ids' => [(string) $product->id],
                    'content_name' => $product->name,
                    'content_type' => 'product',
                    'value' => $product->price,
                    'currency' => 'INR',
                ],
            ],
        ];
    }

    /**
     * Purchase event — used server-rendered on the order success page,
     * so we can build the full <script> block directly.
     */
    public static function purchaseScript(Order $order): string
    {
        $settings = GoogleSetting::current();

        $order->loadMissing('items');

        $itemsGa4 = $order->items->map(fn($i) => [
            'item_id' => (string) $i->product_id,
            'item_name' => $i->product_name,
            'price' => (float) $i->price,
            'quantity' => (int) $i->quantity,
        ])->values();

        $contentIds = $order->items->pluck('product_id')->map(fn($id) => (string) $id)->values();

        $script = '';

        if ($settings->ga4_enabled && $settings->ga4_ev_purchase) {
            $ga4Payload = json_encode([
                'transaction_id' => $order->order_number,
                'currency' => 'INR',
                'value' => (float) $order->grand_total,
                'tax' => (float) $order->tax_amount,
                'shipping' => 0,
                'items' => $itemsGa4,
            ]);

            $script .= "gtag('event', 'purchase', {$ga4Payload});\n";
        }

        if ($settings->gads_enabled && $settings->gads_purchase_label && $settings->gads_conversion_id) {
            $sendTo = "AW-{$settings->gads_conversion_id}/{$settings->gads_purchase_label}";
            $value = $settings->gads_send_order_value ? (float) $order->grand_total : 0;

            $adsPayload = json_encode([
                'send_to' => $sendTo,
                'value' => $value,
                'currency' => $settings->gads_currency ?? 'INR',
                'transaction_id' => $order->order_number,
            ]);

            $script .= "gtag('event', 'conversion', {$adsPayload});\n";
        }

        if ($settings->meta_enabled && $settings->meta_ev_purchase) {
            $metaPayload = json_encode([
                'content_ids' => $contentIds,
                'content_type' => 'product',
                'value' => (float) $order->grand_total,
                'currency' => 'INR',
            ]);

            $eventId = $order->order_number;
            $script .= "fbq('track', 'Purchase', {$metaPayload}, {eventID: '{$eventId}'});\n";
        }

        return $script;
    }

    public static function beginCheckoutScript($cart): string
    {
        $settings = GoogleSetting::current();

        if (!$cart) {
            return '';
        }

        $script = '';

        if ($settings->ga4_enabled && $settings->ga4_ev_begin_checkout) {
            $items = $cart->items->map(fn($i) => [
                'item_id' => (string) $i->product_id,
                'item_name' => $i->product->name ?? 'Product',
                'price' => (float) $i->price,
                'quantity' => (int) $i->quantity,
            ])->values();

            $payload = json_encode([
                'currency' => 'INR',
                'value' => (float) $cart->grand_total,
                'items' => $items,
            ]);

            $script .= "gtag('event', 'begin_checkout', {$payload});\n";
        }

        if ($settings->meta_enabled && $settings->meta_ev_initiate_checkout) {
            $contentIds = $cart->items->pluck('product_id')->map(fn($id) => (string) $id)->values();

            $payload = json_encode([
                'content_ids' => $contentIds,
                'content_type' => 'product',
                'value' => (float) $cart->grand_total,
                'currency' => 'INR',
                'num_items' => $cart->items->sum('quantity'),
            ]);

            $script .= "fbq('track', 'InitiateCheckout', {$payload});\n";
        }

        return $script;
    }
    
    public static function viewItemScript($product): string
{
    $settings = GoogleSetting::current();

    $script = '';

    if ($settings->ga4_enabled && $settings->ga4_ev_view_item) {
        $payload = json_encode([
            'currency' => 'INR',
            'value' => (float) $product->price,
            'items' => [[
                'item_id' => (string) $product->id,
                'item_name' => $product->name,
                'price' => (float) $product->price,
            ]],
        ]);

        $script .= "gtag('event', 'view_item', {$payload});\n";
    }

    if ($settings->meta_enabled && $settings->meta_ev_view_content) {
        $payload = json_encode([
            'content_ids' => [(string) $product->id],
            'content_name' => $product->name,
            'content_type' => 'product',
            'value' => (float) $product->price,
            'currency' => 'INR',
        ]);

        $script .= "fbq('track', 'ViewContent', {$payload});\n";
    }

    return $script;
}

public static function leadScript(?string $sourceLabel = null): string
{
    $settings = GoogleSetting::current();

    if (!($settings->meta_enabled && $settings->meta_ev_lead)) {
        return '';
    }

    $payload = json_encode([
        'content_name' => $sourceLabel ?? 'Enquiry Form',
    ]);

    return "fbq('track', 'Lead', {$payload});\n";
}

public static function loginEvent(): ?array
{
    $settings = GoogleSetting::current();

    if (!($settings->ga4_enabled && $settings->ga4_ev_login)) {
        return null;
    }

    return [
        'ga4' => [
            'name' => 'login',
            'params' => [
                'method' => 'password',
            ],
        ],
    ];
}

public static function signUpEvent(string $method = 'mobile_otp'): ?array
{
    $settings = GoogleSetting::current();

    $events = [];

    if ($settings->ga4_enabled && $settings->ga4_ev_sign_up) {
        $events['ga4'] = [
            'name' => 'sign_up',
            'params' => [
                'method' => $method,
            ],
        ];
    }

    if ($settings->meta_enabled && $settings->meta_ev_complete_reg) {
        $events['meta'] = [
            'name' => 'CompleteRegistration',
            'params' => [
                'content_name' => $method,
                'status' => true,
            ],
        ];
    }

    return empty($events) ? null : $events;
}

}