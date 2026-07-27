@extends('layouts.app')
@section('content')

    <style>
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #FFF8E1;
            color: #F59E0B;
        }

        .status-processing {
            background-color: #E3F2FD;
            color: #1565C0;
        }

        .status-confirmed {
            background-color: #E8F5E9;
            color: #2E7D32;
        }

        .status-shipped {
            background-color: #E0F2F1;
            color: #00796B;
        }

        .status-out_for_delivery {
            background-color: #FFF3E0;
            color: #E65100;
        }

        .status-delivered {
            background-color: #E8F5E9;
            color: #1B5E20;
        }

        .status-cancelled {
            background-color: #FFEBEE;
            color: #B71C1C;
        }

        .status-returned {
            background-color: #F3E5F5;
            color: #6A1B9A;
        }

        .status-refunded {
            background-color: #E8EAF6;
            color: #283593;
        }

        .status-failed {
            background-color: #FFEBEE;
            color: #C62828;
        }
    </style>

    <div class="orders-page-wrapper">
        <section class="dashboard-section">
            <div class="lp-container">
                <div class="dashboard-layout">

                    @include('user._sidebar')

                    <main class="dashboard-main">
                        <button class="dashboard-sidebar-toggle">
                            <i class="fa-solid fa-bars"></i> Menu
                        </button>

                        <div class="orders-header dashboard-content-header">
                            <h1 class="orders-title">MY ORDERS</h1>
                            <p class="orders-subtitle">View and track your luxury purchases</p>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="orders-list">

                            @forelse ($orders as $order)
                                <div class="order-card">

                                    <div class="order-card-header">
                                        <div class="order-info-group">
                                            <span class="order-label">Order Number</span>
                                            <span class="order-value">{{ $order->order_number }}</span>
                                        </div>
                                        <div class="order-info-group">
                                            <span class="order-label">Date Placed</span>
                                            <span class="order-value">{{ $order->created_at->format('F d, Y') }}</span>
                                        </div>
                                        <div class="order-info-group">
                                            <span class="order-label">Total Amount</span>
                                            <span class="order-value">₹{{ number_format($order->grand_total, 2) }}</span>
                                        </div>
                                        <div class="order-status-group">
                                            <span
                                                class="status-badge status-{{ strtolower(str_replace(' ', '_', $order->status)) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="order-card-body">
                                        <div class="order-items">
                                            @foreach ($order->items as $item)
                                                <div class="order-item">
                                                    @php
                                                        $image = $item->product?->display_image 
                                                    @endphp
                                                    <img src="{{ $image ? $image : asset('assets/images/placeholder.png') }}"
                                                        alt="{{ $item->product_name }}" class="order-item-img">
                                                    <div class="order-item-details">
                                                        <h3 class="order-item-title">{{ $item->product_name }}</h3>
                                                        <p class="order-item-meta">{{  $item->product?->weight }}ml |Qty:
                                                            {{ $item->quantity }}</p>
                                                        <p class="order-item-price">₹{{ number_format($item->price, 2) }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="order-actions">

                                            {{-- Invoice: only when available --}}
                                            @if ($order->invoice)
                                                <a href="{{ route('user.orders.invoice', $order->id) }}"
                                                    class="btn-order-action btn-outline">
                                                    Download Invoice
                                                </a>
                                            @endif

                                              @if($order->courier && $order->tracking_number)
    <a href="{{ $order->courier->website_url }}"
        class="btn-order-action btn-outline" target="_blank">
       Track Order
    </a>
@endif

                                            {{-- View Details --}}
                                            <a href="{{ route('user.orders.show', $order->id) }}"
                                                class="btn-order-action btn-solid">
                                                View Details
                                            </a>

                                            {{-- Buy Again: only for delivered orders --}}
                                            @if ($order->status === 'delivered')
                                                <form action="{{ route('user.orders.reorder', $order->id) }}" method="GET"
                                                    style="display:inline;">
                                                    <button type="submit" class="btn-order-action btn-solid">
                                                        Buy Again
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Return/Exchange: delivered within 7 days --}}
                                            <!-- @if ($order->status === 'delivered' && $order->created_at->diffInDays(now()) <= 7)
                                                <button class="btn-order-action btn-outline btn-return"
                                                    data-order-id="{{ $order->id }}">
                                                    Return / Exchange
                                                </button>
                                            @endif -->

                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="empty-state">
                                    <p>You have no orders yet.</p>
                                    <a href="{{ route('home') }}" class="lp-btn lp-btn-solid">Start Shopping</a>
                                </div>
                            @endforelse

                        </div>
                    </main>

                </div>
            </div>
        </section>
    </div>

@endsection