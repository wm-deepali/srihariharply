@extends('layouts.user-auth-app')

@section('title', 'Order Successful | La Pavone')

@section('content')

<section class="thankyou-fullscreen-section">
    <div class="thankyou-card">

        <i class="fa-regular fa-circle-check thankyou-icon"></i>

        <h1 class="thankyou-title">
            THANK YOU!
        </h1>

        <p class="thankyou-subtitle">
            Your order has been placed successfully.<br>

            Your order number is

            <span class="thankyou-order-number">
                {{ $order->order_number }}
            </span>.

            <br>

            @if($order->customer_email)
                We have sent a confirmation email to
                <strong>{{ $order->customer_email }}</strong>.
            @endif

        </p>

        <div class="mt-4">

            <p>
                <strong>Total Amount:</strong>
                ₹{{ number_format($order->grand_total, 2) }}
            </p>

            <p>
                <strong>Payment Status:</strong>

                <span class="badge bg-success">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>

        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">

            <a href="{{ route('user.orders.show', $order->id) }}"
               class="thankyou-action-btn">
                View Order
            </a>

            <a href="{{ route('shop.all') }}"
               class="thankyou-action-btn"
               style="background: transparent; color:#1F5552;border:1px solid #1F5552;">
                Continue Shopping
            </a>

        </div>

    </div>
</section>
@if(!empty($purchaseScript))
<script>
    {!! $purchaseScript !!}
</script>
@endif
@endsection