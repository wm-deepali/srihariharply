<?php

namespace App\Services\Tracking;

use App\Models\GoogleSetting;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCapiService
{
    private const API_VERSION = 'v21.0';
    private const ENDPOINT = 'https://graph.facebook.com';

    /**
     * Send a Purchase event to Meta Conversions API.
     * All PII is hashed before leaving this server. Never logs raw PII or the access token.
     */
    public static function sendPurchase(Order $order, ?string $clientIp = null, ?string $userAgent = null, ?string $fbp = null, ?string $fbc = null): void
    {
        $settings = GoogleSetting::current();

        if (!$settings->meta_enabled || !$settings->meta_pixel_id || !$settings->meta_capi_token) {
            return;
        }

        $order->loadMissing('items');

        $userData = self::buildUserData($order, $clientIp, $userAgent, $fbp, $fbc);

        $customData = [
            'currency' => 'INR',
            'value' => (float) $order->grand_total,
            'content_ids' => $order->items->pluck('product_id')->map(fn($id) => (string) $id)->values(),
            'content_type' => 'product',
            'num_items' => (int) $order->items->sum('quantity'),
        ];

        self::sendEvent($settings, [
            'event_name' => 'Purchase',
            'event_time' => $order->updated_at?->timestamp ?? now()->timestamp,
            // Same ID as the client-side fbq() call for this order — prevents Meta double-counting.
            'event_id' => $order->order_number,
            'action_source' => 'website',
            'event_source_url' => route('order.success', $order->id),
            'user_data' => $userData,
            'custom_data' => $customData,
        ]);
    }

    /**
     * Send a Lead event (contact/enquiry forms) to Meta Conversions API.
     */
    public static function sendLead(string $email, string $phone, string $name, string $sourceLabel, ?string $clientIp = null, ?string $userAgent = null): void
    {
        $settings = GoogleSetting::current();

        if (!$settings->meta_enabled || !$settings->meta_pixel_id || !$settings->meta_capi_token) {
            return;
        }

        $userData = [
            'em' => [self::hash($email)],
            'ph' => [self::hash(self::normalizePhone($phone))],
        ];

        if ($clientIp) {
            $userData['client_ip_address'] = $clientIp;
        }

        if ($userAgent) {
            $userData['client_user_agent'] = $userAgent;
        }

        self::sendEvent($settings, [
            'event_name' => 'Lead',
            'event_time' => now()->timestamp,
            'event_id' => 'lead_' . md5($email . $sourceLabel . now()->timestamp),
            'action_source' => 'website',
            'user_data' => array_filter($userData),
            'custom_data' => [
                'content_name' => $sourceLabel,
            ],
        ]);
    }

    /**
     * Core sender. Never throws — logs failure and returns silently so it
     * can never break the calling flow (order placement, form submission, etc).
     */
    private static function sendEvent(GoogleSetting $settings, array $eventData): void
    {
        $payload = [
            'data' => [$eventData],
        ];

        // Only attach test_event_code if explicitly configured — prevents
        // production events from silently landing in Meta's test console.
        if (!empty($settings->meta_test_event_code)) {
            $payload['test_event_code'] = $settings->meta_test_event_code;
        }

        try {
            $response = Http::timeout(8)
                ->retry(2, 500) // brief retry for transient network issues
                ->post(
                    self::ENDPOINT . '/' . self::API_VERSION . '/' . $settings->meta_pixel_id . '/events',
                    array_merge($payload, [
                        'access_token' => $settings->meta_capi_token,
                    ])
                );

            if (!$response->successful()) {
                // Log status + Meta's error body, but never the access token or hashed PII.
                Log::warning('Meta CAPI event failed', [
                    'event_name' => $eventData['event_name'],
                    'event_id' => $eventData['event_id'],
                    'status' => $response->status(),
                    'error' => $response->json('error.message') ?? 'Unknown error',
                ]);
            }
        } catch (\Throwable $e) {
            // Network failure, timeout, etc. Never let this bubble up.
            Log::warning('Meta CAPI request exception', [
                'event_name' => $eventData['event_name'] ?? 'unknown',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Build hashed user_data block for an order. Raw PII never leaves this method.
     */
    private static function buildUserData(Order $order, ?string $clientIp, ?string $userAgent, ?string $fbp, ?string $fbc): array
    {
        $userData = [];

        if (!empty($order->customer_email)) {
            $userData['em'] = [self::hash($order->customer_email)];
        }

        if (!empty($order->customer_phone)) {
            $userData['ph'] = [self::hash(self::normalizePhone($order->customer_phone))];
        }

        if ($clientIp) {
            $userData['client_ip_address'] = $clientIp;
        }

        if ($userAgent) {
            $userData['client_user_agent'] = $userAgent;
        }

        // fbp/fbc cookies improve match quality but are optional and never contain PII themselves.
        if ($fbp) {
            $userData['fbp'] = $fbp;
        }

        if ($fbc) {
            $userData['fbc'] = $fbc;
        }

        return $userData;
    }

    /**
     * Meta requires: lowercase, trimmed, SHA-256 hashed.
     */
    private static function hash(string $value): string
    {
        return hash('sha256', strtolower(trim($value)));
    }

    /**
     * Meta expects phone in digits only, no leading zeros, no country-code symbol issues.
     * Assumes Indian 10-digit numbers; prefixes country code 91 if missing.
     */
    private static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) === 10) {
            $digits = '91' . $digits;
        }

        return $digits;
    }
}