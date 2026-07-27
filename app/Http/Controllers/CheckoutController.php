<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderAdminMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\CustomerAddress;
use App\Models\Setting;
use App\Models\SmtpSetting;
use App\Models\State;
use App\Services\Sms\SmsDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\PaymentSetting;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Helpers\MailHelper;
use App\Services\StockAlertService;

class CheckoutController extends Controller
{

    // Inject in constructor
    public function __construct(protected StockAlertService $alertService)
    {
    }

    public function checkout()
    {
        $customer = auth('customer')->user();

        $cart = Cart::with([
            'items.product.images',
            'items.product.category',
        ])
            ->where('user_id', $customer->id)
            ->first();

        if ($cart) {
            $cart->recalculateTotals();
            $cart->refresh();
        }

        $customer = auth('customer')->user();

        $addresses = $customer->addresses()
            ->with(['state', 'city'])
            ->orderByDesc('is_default')
            ->get();

        $defaultAddress = $addresses->firstWhere('is_default', true);

        $states = State::orderBy('name')
            ->get();
            
                $beginCheckoutScript = \App\Services\Tracking\PixelTracker::beginCheckoutScript($cart);


        return view(
            'front-pages.checkout',
            compact(
                'cart',
                'customer',
                'addresses',
                'defaultAddress',
                'states',
                'beginCheckoutScript' // 👈 new
            )
        );
    }

    public function storeAddress(Request $request)
    {
        $customer = auth('customer')->user();

        if ($request->address_id) {

            CustomerAddress::where(
                'id',
                $request->address_id
            )->update([

                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address_line_1' => $request->address_line_1,
                        'address_line_2' => $request->address_line_2,
                        'state_id' => $request->state_id,
                        'city_id' => $request->city_id,
                        'pincode' => $request->pincode,
                        'address_type' => $request->address_type

                    ]);

        } else {


            CustomerAddress::where(
                'customer_id',
                $customer->id
            )->update([
                        'is_default' => 0
                    ]);

            CustomerAddress::create([
                'customer_id' => $customer->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'country' => 'India',
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'pincode' => $request->pincode,
                'address_type' => $request->address_type ?? 'home',
                'is_default' => 1,
            ]);

        }

        $cart = Cart::where('user_id', $customer->id)->first();

        if ($cart) {
            $cart->recalculateTotals();
        }

        return response()->json([
            'success' => true
        ]);
    }


    public function changeDefaultAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:customer_addresses,id'
        ]);

        $customer = auth('customer')->user();

        CustomerAddress::where(
            'customer_id',
            $customer->id
        )->update([
                    'is_default' => 0
                ]);

        CustomerAddress::where(
            'id',
            $request->address_id
        )
            ->where('customer_id', $customer->id)
            ->update([
                'is_default' => 1
            ]);

        $cart = Cart::where(
            'user_id',
            $customer->id
        )->first();

        if ($cart) {
            $cart->recalculateTotals();
        }

        return response()->json([
            'success' => true
        ]);
    }


    public function placeOrder(Request $request)
    {

        $customer = auth('customer')->user();

        $request->validate([
            'payment_method' => 'required|in:cod,razorpay',
        ]);

        $address = null;

        /*
        |--------------------------------------------------------------------------
        | Existing Address
        |--------------------------------------------------------------------------
        */

        if ($request->filled('address_id')) {

            $address = CustomerAddress::where(
                'customer_id',
                $customer->id
            )->find($request->address_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Address If Not Exists
        |--------------------------------------------------------------------------
        */

        if (!$address) {

            $request->validate([

                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required',

                'address_line_1' => 'required',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'pincode' => 'required',
            ]);

            CustomerAddress::where(
                'customer_id',
                $customer->id
            )->update([
                        'is_default' => 0
                    ]);

            $address = CustomerAddress::create([

                'customer_id' => $customer->id,

                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,

                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,

                'state_id' => $request->state_id,
                'city_id' => $request->city_id,

                'pincode' => $request->pincode,

                'address_type' => 'home',

                'is_default' => 1,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Recalculate GST Using New Address
            |--------------------------------------------------------------------------
            */

            $cart = Cart::where(
                'user_id',
                $customer->id
            )->first();

            if ($cart) {

                $cart->recalculateTotals();
                $cart->refresh();
            }
        }

        $cart = Cart::with([
            'items.product'
        ])
            ->where('user_id', $customer->id)
            ->first();

        if (!$cart || $cart->items->count() == 0) {

            return response()->json([
                'success' => false,
                'message' => 'Cart is empty.'
            ]);
        }

        DB::beginTransaction();

        try {

            $order = Order::create([

                'customer_id' => $customer->id,
                'address_id' => $address->id,

                'order_number' =>
                    'ORD-' . strtoupper(uniqid()),

                'customer_name' => $address->name,
                'customer_email' => $address->email,
                'customer_phone' => $address->phone,

                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,

                'state_id' => $address->state_id,
                'city_id' => $address->city_id,
                'pincode' => $address->pincode,

                'coupon_code' => $cart->coupon_code,

                'subtotal' => $cart->subtotal,
                'discount' => $cart->discount,
                'tax_amount' => $cart->tax_amount,
                'grand_total' => $cart->grand_total,

                'gst_type' => $cart->gst_type,

                'cgst_rate' => $cart->cgst_rate,
                'sgst_rate' => $cart->sgst_rate,
                'igst_rate' => $cart->igst_rate,

                'cgst_amount' => $cart->cgst_amount,
                'sgst_amount' => $cart->sgst_amount,
                'igst_amount' => $cart->igst_amount,

                'payment_method' => $request->payment_method,

                'payment_status' =>
                    $request->payment_method == 'cod'
                    ? 'pending'
                    : 'pending',

                'status' => 'pending',
            ]);

            $order->statusHistory()->create([
                'status' => 'pending',
                'remarks' => 'Order placed successfully',
            ]);

            \App\Models\Notification::create([
                'customer_id' => $customer->id,
                'title' => 'Order Placed',
                'message' => 'Your order ' . $order->order_number . ' has been placed successfully.',
                'icon' => 'fa-cart-shopping',
                'color' => 'order-icon',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => 'pending',
                ],
            ]);

            foreach ($cart->items as $item) {

                OrderItem::create([

                    'order_id' => $order->id,

                    'product_id' => $item->product_id,

                    'product_name' =>
                        $item->product->name ?? 'Product',

                    'quantity' => $item->quantity,

                    'price' => $item->price,

                    'total' => $item->total,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | COD Order
            |--------------------------------------------------------------------------
            */

            if ($request->payment_method == 'cod') {

                $invoiceNumber =
                    AdminSettingController::generateInvoiceNumber();

                Invoice::create([
                    'order_id' => $order->id,
                    'invoice_number' => $invoiceNumber,
                    'invoice_date' => now()->toDateString(),

                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,

                    'billing_address' => collect([
                        $order->address_line_1,
                        $order->address_line_2,
                        $order->city?->name ?? '',
                        $order->state?->name ?? '',
                        $order->pincode,
                    ])->filter()->implode(', '),

                    'subtotal' => $order->subtotal,
                    'discount' => $order->discount,
                    'tax_amount' => $order->tax_amount,
                    'grand_total' => $order->grand_total,

                    'gst_type' => $order->gst_type,

                    'cgst_rate' => $order->cgst_rate,
                    'sgst_rate' => $order->sgst_rate,
                    'igst_rate' => $order->igst_rate,

                    'cgst_amount' => $order->cgst_amount,
                    'sgst_amount' => $order->sgst_amount,
                    'igst_amount' => $order->igst_amount,

                    'status' => 'generated',
                ]);

                $cart->items()->delete();
                $cart->delete();

                // COD — deduct stock before committing
                $this->deductOrderStock($order);
                $this->alertService->sendAlertEmailIfNeeded(); // ← add this


                DB::commit();
                
                // 👇 new — dispatch after commit so order is guaranteed saved
\App\Jobs\SendMetaCapiPurchase::dispatch(
    $order->id,
    $request->ip(),
    $request->userAgent(),
    $request->cookie('_fbp'),
    $request->cookie('_fbc'),
);

                $this->sendOrderEmails($order);
                $this->sendOrderSms($order);

                return response()->json([
                    'success' => true,
                    'type' => 'cod',
                    'message' => 'Order placed successfully.',
                    'redirect_url' => route(
                        'order.success',
                        $order->id
                    )
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Razorpay Order
            |--------------------------------------------------------------------------
            */

            $paymentSetting = PaymentSetting::first();

            $keyId = $paymentSetting->live_mode
                ? $paymentSetting->live_key_id
                : $paymentSetting->test_key_id;

            $keySecret = $paymentSetting->live_mode
                ? $paymentSetting->live_key_secret
                : $paymentSetting->test_key_secret;

            $api = new Api(
                $keyId,
                $keySecret
            );
            $razorpayOrder = $api->order->create([

                'receipt' => $order->order_number,

                'amount' =>
                    round($order->grand_total * 100),

                'currency' => 'INR',

                'payment_capture' => 1
            ]);

            $order->update([
                'razorpay_order_id' =>
                    $razorpayOrder['id']
            ]);

            DB::commit();

            return response()->json([

                'success' => true,

                'type' => 'razorpay',

                'order_id' => $order->id,

                'razorpay_order_id' =>
                    $razorpayOrder['id'],

                'amount' =>
                    round($order->grand_total * 100),

                'key' => $keyId,

                'customer_name' =>
                    $order->customer_name,

                'customer_email' =>
                    $order->customer_email,

                'customer_phone' =>
                    $order->customer_phone,
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function razorpaySuccess(Request $request)
    {
        $request->validate([

            'order_id' => 'required|exists:orders,id',

            'razorpay_payment_id' => 'required',

            'razorpay_order_id' => 'required',

            'razorpay_signature' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $order = Order::with('items')
                ->findOrFail($request->order_id);

            /*
            |--------------------------------------------------------------------------
            | Verify Signature
            |--------------------------------------------------------------------------
            */

            $paymentSetting = PaymentSetting::first();

            $keyId = $paymentSetting->live_mode
                ? $paymentSetting->live_key_id
                : $paymentSetting->test_key_id;

            $keySecret = $paymentSetting->live_mode
                ? $paymentSetting->live_key_secret
                : $paymentSetting->test_key_secret;

            $api = new Api(
                $keyId,
                $keySecret
            );

            $attributes = [

                'razorpay_order_id' =>
                    $request->razorpay_order_id,

                'razorpay_payment_id' =>
                    $request->razorpay_payment_id,

                'razorpay_signature' =>
                    $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature(
                $attributes
            );

            /*
            |--------------------------------------------------------------------------
            | Mark Order Paid
            |--------------------------------------------------------------------------
            */

            $order->update([

                'payment_status' => 'paid',

                'razorpay_payment_id' =>
                    $request->razorpay_payment_id,

                'razorpay_signature' =>
                    $request->razorpay_signature,

                'transaction_id' =>
                    $request->razorpay_payment_id,

                'status' => 'processing',
            ]);

            $order->statusHistory()->create([
                'status' => 'processing',
                'remarks' => 'Payment received successfully via Razorpay',
            ]);


            \App\Models\Notification::create([
                'customer_id' => $order->customer_id,
                'title' => 'Payment Successful',
                'message' => 'Payment received for order ' . $order->order_number . '. Your order is now being processed.',
                'icon' => 'fa-credit-card',
                'color' => 'success-icon',
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => 'processing',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Generate Invoice
            |--------------------------------------------------------------------------
            */

            if (!$order->invoice) {

                $invoiceNumber =
                    AdminSettingController::generateInvoiceNumber();

                Invoice::create([

                    'order_id' => $order->id,

                    'invoice_number' =>
                        $invoiceNumber,

                    'invoice_date' =>
                        now()->toDateString(),

                    'customer_name' =>
                        $order->customer_name,

                    'customer_email' =>
                        $order->customer_email,

                    'customer_phone' =>
                        $order->customer_phone,

                    'billing_address' =>
                        collect([
                            $order->address_line_1,
                            $order->address_line_2,
                            $order->city?->name ?? '',
                            $order->state?->name ?? '',
                            $order->pincode,
                        ])->filter()->implode(', '),

                    'subtotal' =>
                        $order->subtotal,

                    'discount' =>
                        $order->discount,

                    'tax_amount' =>
                        $order->tax_amount,

                    'grand_total' =>
                        $order->grand_total,

                    'gst_type' =>
                        $order->gst_type,

                    'cgst_rate' =>
                        $order->cgst_rate,

                    'sgst_rate' =>
                        $order->sgst_rate,

                    'igst_rate' =>
                        $order->igst_rate,

                    'cgst_amount' =>
                        $order->cgst_amount,

                    'sgst_amount' =>
                        $order->sgst_amount,

                    'igst_amount' =>
                        $order->igst_amount,

                    'status' => 'generated',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Clear Cart
            |--------------------------------------------------------------------------
            */

            $cart = Cart::where(
                'user_id',
                $order->customer_id
            )->first();

            if ($cart) {

                $cart->items()->delete();

                $cart->delete();
            }

            // Razorpay — deduct stock after payment verified, before committing
            $this->deductOrderStock($order);
            $this->alertService->sendAlertEmailIfNeeded(); // ← add this


            DB::commit();

// 👇 new
\App\Jobs\SendMetaCapiPurchase::dispatch(
    $order->id,
    $request->ip(),
    $request->userAgent(),
    $request->cookie('_fbp'),
    $request->cookie('_fbc'),
);

            $this->sendOrderEmails($order);
            $this->sendOrderSms($order);

            return response()->json([

                'success' => true,

                'redirect_url' =>
                    route(
                        'order.success',
                        $order->id
                    ),
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' =>
                    $e->getMessage()
            ], 422);
        }
    }

    public function orderSuccess(Order $order)
    {
          $purchaseScript = \App\Services\Tracking\PixelTracker::purchaseScript($order);
        return view(
            'front-pages.thank-you',
            compact('order', 'purchaseScript') // 👈 new
        );
    }


    protected function sendOrderEmails(Order $order): void
    {
        $order->loadMissing([
            'items.product',
            'state',
            'city',
        ]);

        /*
         |--------------------------------------------------------------------------
         | Order Items HTML
         |--------------------------------------------------------------------------
         */
        $orderItems = '';

        foreach ($order->items as $item) {

            $productImage = $item->product?->defaultImage?->image
                ?? $item->product?->images?->first()?->image
                ?? null;

            $imageHtml = $productImage
                ? "<img src='" . asset('storage/' . $productImage) . "' alt='{$item->product_name}' style='width:56px;height:56px;object-fit:cover;border-radius:4px;border:1px solid #d0d8d7;display:block;'>"
                : "<span style='display:block;width:56px;height:56px;background:#e8efee;border-radius:4px;border:1px solid #d0d8d7;'></span>";

            $weightHtml = $item->product?->weight
                ? "<div style='font-size:11px;color:#7a9e9c;'>{$item->product?->weight}ml</div>"
                : '';

            $orderItems .= "
            <div style='display:table;width:100%;border-bottom:1px solid #e6eae9;padding:14px 0;'>
                <div style='display:table-cell;width:60px;vertical-align:middle;padding-right:14px;'>
                    {$imageHtml}
                </div>
                <div style='display:table-cell;vertical-align:middle;'>
                    <div style='font-size:13px;font-weight:600;color:#1a1a1a;margin-bottom:3px;'>{$item->product_name}</div>
                    {$weightHtml}
                    <div style='font-size:11px;color:#7a9e9c;'>Qty: {$item->quantity}</div>
                </div>
                <div style='display:table-cell;vertical-align:middle;text-align:right;font-size:14px;font-weight:700;color:#1F5552;white-space:nowrap;'>
                    ₹ " . number_format($item->total, 2) . "
                </div>
            </div>
        ";
        }

        /*
        |--------------------------------------------------------------------------
        | Order Summary HTML
        |--------------------------------------------------------------------------
        */
        $orderSummary = "
        <div style='margin-top:16px;'>
            <div style='display:table;width:100%;padding:5px 0;'>
                <span style='display:table-cell;font-size:13px;color:#666;'>Subtotal</span>
                <span style='display:table-cell;text-align:right;font-size:13px;color:#333;'>
                    ₹ " . number_format($order->subtotal, 2) . "
                </span>
            </div>
    ";

        if ($order->discount > 0) {
            $discountLabel = 'Discount' . ($order->coupon_code ? " ({$order->coupon_code})" : '');
            $orderSummary .= "
            <div style='display:table;width:100%;padding:5px 0;'>
                <span style='display:table-cell;font-size:13px;color:#2e7d32;font-weight:500;'>{$discountLabel}</span>
                <span style='display:table-cell;text-align:right;font-size:13px;color:#2e7d32;font-weight:500;'>
                    − ₹ " . number_format($order->discount, 2) . "
                </span>
            </div>
        ";
        }

        if ($order->tax_amount > 0) {

            if ($order->gst_type === 'igst' && $order->igst_amount > 0) {

                $orderSummary .= "
                <div style='display:table;width:100%;padding:5px 0;'>
                    <span style='display:table-cell;font-size:13px;color:#666;'>IGST ({$order->igst_rate}%)</span>
                    <span style='display:table-cell;text-align:right;font-size:13px;color:#333;'>
                        ₹ " . number_format($order->igst_amount, 2) . "
                    </span>
                </div>
            ";

            } else {

                if ($order->cgst_amount > 0) {
                    $orderSummary .= "
                    <div style='display:table;width:100%;padding:5px 0;'>
                        <span style='display:table-cell;font-size:13px;color:#666;'>CGST ({$order->cgst_rate}%)</span>
                        <span style='display:table-cell;text-align:right;font-size:13px;color:#333;'>
                            ₹ " . number_format($order->cgst_amount, 2) . "
                        </span>
                    </div>
                ";
                }

                if ($order->sgst_amount > 0) {
                    $orderSummary .= "
                    <div style='display:table;width:100%;padding:5px 0;'>
                        <span style='display:table-cell;font-size:13px;color:#666;'>SGST ({$order->sgst_rate}%)</span>
                        <span style='display:table-cell;text-align:right;font-size:13px;color:#333;'>
                            ₹ " . number_format($order->sgst_amount, 2) . "
                        </span>
                    </div>
                ";
                }
            }
        }

        $orderSummary .= "
            <hr style='border:none;border-top:1px solid #d4dbd9;margin:10px 0;'>
            <div style='display:table;width:100%;padding:5px 0;'>
                <span style='display:table-cell;font-size:15px;font-weight:600;color:#1a1a1a;'>Grand Total</span>
                <span style='display:table-cell;text-align:right;font-size:16px;font-weight:700;color:#1F5552;'>
                    ₹ " . number_format($order->grand_total, 2) . "
                </span>
            </div>
        </div>
    ";
        /*
        |--------------------------------------------------------------------------
        | Shipping Address HTML
        |--------------------------------------------------------------------------
        */
        $shippingAddress = "
        <div>
            <strong>{$order->customer_name}</strong><br>
            {$order->address_line_1}
    ";

        if (!empty($order->address_line_2)) {
            $shippingAddress .= "<br>{$order->address_line_2}";
        }

        $shippingAddress .= "
            <br>{$order->city?->name}, {$order->state?->name} - {$order->pincode}
            <br>📞 {$order->customer_phone}
        </div>
    ";

        /*
        |--------------------------------------------------------------------------
        | Common Variables
        |--------------------------------------------------------------------------
        */
        $variables = [

            '{customer_name}' => $order->customer_name,

            '{order_number}' => $order->order_number,
            '{order_date}' => $order->created_at->format('d M Y'),
            '{grand_total}' => '₹' . number_format($order->grand_total, 2),

            '{payment_method}' => ucfirst($order->payment_method),
            '{payment_status}' => ucfirst($order->payment_status),
            '{transaction_id}' => $order->transaction_id ?? 'N/A',

            '{order_url}' => route('order.success', $order->id),

            '{order_items}' => $orderItems,
            '{order_summary}' => $orderSummary,
            '{shipping_address}' => $shippingAddress,
        ];

        /*
        |--------------------------------------------------------------------------
        | Customer - Order Confirmed
        |--------------------------------------------------------------------------
        */
        \App\Services\Email\EmailDispatcher::send(
            'order-confirmed',
            $order->customer_email,
            $variables,
            $order->customer_name
        );

        /*
        |--------------------------------------------------------------------------
        | Customer - Payment Received
        |--------------------------------------------------------------------------
        */
        if ($order->payment_status === 'paid') {

            \App\Services\Email\EmailDispatcher::send(
                'payment-received',
                $order->customer_email,
                array_merge($variables, [
                    '{payment_amount}' => '₹' . number_format($order->grand_total, 2),
                    '{transaction_id}' => $order->transaction_id ?? $order->razorpay_payment_id ?? 'N/A',
                    '{invoice_url}' => route('order.success', $order->id),
                ]),
                $order->customer_name
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Admin - New Order Alert
        |--------------------------------------------------------------------------
        */
        $adminEmail = Setting::first()?->admin_email;

        if ($adminEmail) {

            \App\Services\Email\EmailDispatcher::send(
                'new-order-alert',
                $adminEmail,
                array_merge($variables, [
                    '{admin_order_url}' => route('admin.orders.show', $order->id),
                ])
            );
        }
    }



    protected function deductOrderStock(Order $order): void
    {
        /** @var \App\Services\StockService $stockService */
        $stockService = app(\App\Services\StockService::class);

        foreach ($order->items as $item) {
            $product = $item->product;

            if (!$product)
                continue;

            try {
                $stockService->debit(
                    $product,
                    $item->quantity,
                    'order',
                    $order,           // reference — links history entry to this order
                    null,             // no admin user, this is a customer action
                    null,             // no note
                    true              // allowNegative: true so an order never fails due to stock race
                );
            } catch (\Exception $e) {
                // Log but don't block the order — stock can be corrected manually
                \Log::warning("Stock debit failed for product {$product->id} on order {$order->id}: " . $e->getMessage());
            }
        }
    }

    protected function sendOrderSms(Order $order): void
    {
        $mobile = $order->customer_phone;

        if (empty($mobile)) {
            return;
        }

        SmsDispatcher::send('order-confirmed', $mobile, [
            '{customer_name}' => $order->customer_name,
            '{order_id}' => $order->order_number,
            '{order_amount}' => '₹' . number_format($order->grand_total, 2),
            '{payment_method}' => ucfirst($order->payment_method),
            '{expected_delivery}' => now()->addDays(5)->format('d M Y'),
            '{brand_name}' => config('app.name'),
        ]);

        if ($order->payment_status === 'paid') {

            SmsDispatcher::send('payment-received', $mobile, [
                '{payment_amount}' => '₹' . number_format($order->grand_total, 2),
                '{order_id}' => $order->order_number,
                '{payment_method}' => ucfirst($order->payment_method),
                '{transaction_id}' => $order->transaction_id,
                '{customer_name}' => $order->customer_name,
                '{brand_name}' => config('app.name'),
            ]);
        }

    }


}