<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Order Has Been Delivered – {{ $order->order_number }}</title>
  <style>
    /* ── Reset ── */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Georgia', serif;
      background-color: #e8eae6;
      color: #1a1a1a;
      -webkit-font-smoothing: antialiased;
    }

    /* ── Wrapper ── */
    .wrapper {
      max-width: 620px;
      margin: 30px auto;
      background: #ffffff;
      border-radius: 4px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    }

    /* ── Header ── */
    .email-header {
      background: #1F5552;
      text-align: center;
      padding: 32px 30px 28px;
      border-bottom: 3px solid #174743;
    }
    .email-header .brand {
      font-family: 'Georgia', serif;
      font-size: 22px;
      font-weight: 700;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #f4f5f2;
      margin-top: 6px;
    }
    .email-header .tagline {
      font-size: 11px;
      color: #a8c4c2;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-top: 5px;
    }

    /* ── Meta Bar ── */
    .meta-bar {
      display: table;
      width: 100%;
      background: #163e3c;
      border-bottom: 2px solid #0f2c2a;
    }
    .meta-cell {
      display: table-cell;
      padding: 14px 16px;
      text-align: center;
      border-right: 1px solid #1F5552;
      vertical-align: middle;
    }
    .meta-cell:last-child { border-right: none; }
    .meta-label {
      display: block;
      font-size: 9px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #7ab0ad;
      margin-bottom: 3px;
    }
    .meta-value {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #f4f5f2;
    }

    /* ── Body ── */
    .body {
      padding: 36px 36px 28px;
      background: #ffffff;
    }
    .greeting {
      font-family: 'Georgia', serif;
      font-size: 16px;
      color: #1F5552;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .intro {
      font-size: 13px;
      color: #555;
      line-height: 1.7;
      margin-bottom: 28px;
    }

    /* ── Section Title ── */
    .section-title {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #1F5552;
      border-bottom: 2px solid #1F5552;
      padding-bottom: 7px;
      margin-bottom: 16px;
    }

    /* ── Order Items ── */
    .item-row {
      display: table;
      width: 100%;
      border-bottom: 1px solid #e6eae9;
      padding: 14px 0;
    }
    .item-img-cell {
      display: table-cell;
      width: 60px;
      vertical-align: middle;
      padding-right: 14px;
    }
    .item-img {
      width: 56px;
      height: 56px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid #d0d8d7;
    }
    .item-img-placeholder {
      display: block;
      width: 56px;
      height: 56px;
      background: #e8efee;
      border-radius: 4px;
      border: 1px solid #d0d8d7;
    }
    .item-detail-cell {
      display: table-cell;
      vertical-align: middle;
    }
    .item-name {
      font-size: 13px;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 3px;
    }
    .item-meta {
      font-size: 11px;
      color: #7a9e9c;
    }
    .item-price-cell {
      display: table-cell;
      vertical-align: middle;
      text-align: right;
      font-size: 14px;
      font-weight: 700;
      color: #1F5552;
      white-space: nowrap;
    }

    /* ── Info Grid ── */
    .info-grid {
      display: table;
      width: 100%;
      margin-top: 8px;
    }
    .info-col {
      display: table-cell;
      width: 50%;
      padding-right: 16px;
      vertical-align: top;
    }
    .info-col:last-child { padding-right: 0; padding-left: 16px; }
    .info-box {
      background: #f4f5f2;
      border: 1px solid #d4dbd9;
      border-left: 3px solid #1F5552;
      border-radius: 3px;
      padding: 14px 16px;
      font-size: 12px;
      color: #444;
      line-height: 1.7;
    }
    .info-box strong {
      color: #1F5552;
      font-size: 13px;
      display: block;
      margin-bottom: 5px;
    }
    .info-box p { margin: 0; }

    /* ── CTA ── */
    .cta-section {
      text-align: center;
      margin: 32px 0 10px;
    }
    .cta-btn {
      display: inline-block;
      background: #1F5552;
      color: #f4f5f2 !important;
      text-decoration: none;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 14px 36px;
      border-radius: 2px;
    }

    /* ── Help Strip ── */
    .help-strip {
      background: #f4f5f2;
      border-top: 1px solid #d4dbd9;
      border-bottom: 1px solid #d4dbd9;
      text-align: center;
      padding: 20px 30px;
      font-size: 12px;
      color: #555;
      line-height: 1.7;
    }
    .help-strip a {
      color: #1F5552;
      font-weight: 600;
      text-decoration: none;
    }

    /* ── Footer ── */
    .email-footer {
      background: #1F5552;
      text-align: center;
      padding: 24px 30px;
    }
    .brand-footer {
      display: block;
      font-family: 'Georgia', serif;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #f4f5f2;
      margin-bottom: 12px;
    }
    .email-footer p {
      font-size: 11px;
      color: #7ab0ad;
      line-height: 1.8;
    }
    .email-footer a {
      color: #a8c4c2;
      text-decoration: none;
    }
  </style>
</head>

<body>

  <div class="wrapper">

    {{-- Header --}}
    <div class="email-header">
      @if(!empty($logoPath))
        <div style="background:#f4f5f2;display:inline-block;padding:10px 18px;border-radius:4px;margin-bottom:12px;">
          <img src="{{ $message->embed($logoPath) }}" alt="{{ $settings->site_name }}"
            style="max-height:70px;max-width:220px;">
        </div>
      @endif

      @if(empty($logoPath))
        <div class="brand">
          {{ $settings->site_name ?? config('app.name') }}
        </div>
      @endif

      @if(!empty($settings->tagline))
        <div class="tagline">
          {{ $settings->tagline }}
        </div>
      @endif

    </div>

    {{-- Hero --}}
    <div style="background: linear-gradient(135deg, #1F5552 0%, #2a6e6a 100%); padding: 40px; text-align: center;">
      <table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto 14px auto;">
        <tr>
          <td width="54" height="54" align="center" valign="middle"
              style="width:54px;height:54px;background:#f4f5f2;border-radius:50%;">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1F5552" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </td>
        </tr>
      </table>
      <h1 style="font-family:'Georgia',serif;font-size:24px;font-weight:600;color:#f4f5f2;margin-bottom:6px;">
        Your order has been delivered!
      </h1>
      <p style="font-size:13px;color:#a8c4c2;">
        We hope you love your new piece.<br>
        Thank you for choosing {{ $settings->site_name }}.
      </p>
    </div>

    {{-- Meta Bar --}}
    <div class="meta-bar">
      <div class="meta-cell">
        <span class="meta-label">Order No.</span>
        <span class="meta-value">{{ $order->order_number }}</span>
      </div>
      <div class="meta-cell">
        <span class="meta-label">Delivered On</span>
        <span class="meta-value">{{ now()->format('d M Y') }}</span>
      </div>
      <div class="meta-cell">
        <span class="meta-label">Items</span>
        <span class="meta-value">{{ $order->items->count() }}</span>
      </div>
      <div class="meta-cell">
        <span class="meta-label">Order Total</span>
        <span class="meta-value">₹ {{ number_format($order->grand_total, 2) }}</span>
      </div>
    </div>

    {{-- Body --}}
    <div class="body">

      <p class="greeting">Dear {{ $order->customer_name }},</p>
      <p class="intro">
        Your {{ $settings->site_name }} order has been successfully delivered. We hope your new piece brings
        you joy and that you're delighted with the craftsmanship. Each item in our
        collection is handcrafted with care, and we're grateful you chose us.
      </p>

      {{-- Items --}}
      <div class="section-title">What You Received</div>

      @foreach($order->items as $item)
        <div class="item-row">
          <div class="item-img-cell">
            @if(isset($productImages[$item->id]))
              <img src="{{ $message->embed($productImages[$item->id]) }}" alt="{{ $item->product_name }}" class="item-img">
            @else
              <span class="item-img-placeholder"></span>
            @endif
          </div>
          <div class="item-detail-cell">
            <div class="item-name">{{ $item->product_name }}</div>
            <div class="item-meta">Qty: {{ $item->quantity }}</div>
          </div>
          <div class="item-price-cell">₹ {{ number_format($item->total, 2) }}</div>
        </div>
      @endforeach

      {{-- Review Prompt --}}
      <div style="background:#f4f5f2;border:1px solid #d4dbd9;border-left:3px solid #1F5552;border-radius:3px;padding:22px 24px;margin:28px 0;text-align:center;">
        <div style="font-size:22px;margin-bottom:8px;">⭐⭐⭐⭐⭐</div>
        <div style="font-family:'Georgia',serif;font-size:16px;color:#1F5552;font-weight:600;margin-bottom:6px;">
          How was your experience?
        </div>
        <p style="font-size:12px;color:#777;margin-bottom:16px;line-height:1.6;">
          Your feedback helps our artisans and helps other customers discover beautiful pieces.
          It only takes a moment.
        </p>
        <a href="{{ url('/user/orders/' . $order->id) }}"
          style="display:inline-block;background:#1F5552;color:#f4f5f2 !important;text-decoration:none;font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;padding:12px 24px;border-radius:2px;">
          Write a Review
        </a>
      </div>

      {{-- Delivery + Payment --}}
      <div class="info-grid">
        <div class="info-col">
          <div class="section-title">Delivered To</div>
          <div class="info-box">
            <strong>{{ $order->customer_name }}</strong>
            <p>
              {{ $order->address_line_1 }}
              @if($order->address_line_2), {{ $order->address_line_2 }}@endif<br>
              {{ $order->city?->name }}, {{ $order->state?->name }} – {{ $order->pincode }}
            </p>
          </div>
        </div>
        <div class="info-col">
          <div class="section-title">Payment Summary</div>
          <div class="info-box">
            <strong style="font-size:18px;color:#1F5552;">₹ {{ number_format($order->grand_total, 2) }}</strong>
            <p style="margin-top:6px;">
              {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}<br>
              <span style="color:#1F5552;font-weight:600;">
                {{ $order->payment_status === 'cod' ? 'Collected on Delivery' : 'Paid' }}
              </span>
            </p>
          </div>
        </div>
      </div>

      {{-- Need Help / Return --}}
      <div style="background:#f4f5f2;border:1px solid #d4dbd9;border-left:3px solid #1F5552;border-radius:3px;padding:16px 20px;margin-top:24px;display:table;width:100%;">
        <div style="display:table-cell;vertical-align:middle;">
          <div style="font-size:13px;font-weight:600;color:#1F5552;margin-bottom:3px;">
            Something not right?
          </div>
          <div style="font-size:12px;color:#777;">
            We accept returns within 7 days of delivery for eligible items.
          </div>
        </div>
        <div style="display:table-cell;vertical-align:middle;text-align:right;white-space:nowrap;padding-left:16px;">
          <a href="{{ url('/user/orders/' . $order->id) }}"
            style="font-size:11px;font-weight:600;color:#1F5552;text-decoration:none;border:1px solid #1F5552;padding:8px 14px;border-radius:2px;text-transform:uppercase;letter-spacing:0.06em;">
            Request Return
          </a>
        </div>
      </div>

      {{-- CTA --}}
      <div class="cta-section">
        <a href="{{ url('/user/orders/' . $order->id) }}" class="cta-btn">
          View Order Details
        </a>
      </div>

    </div>

    {{-- Help --}}
    <div class="help-strip">
      <p>
        Questions about your delivery? We're here to help.<br>

        @if($settings?->support_email)
          Email us at
          <a href="mailto:{{ $settings->support_email }}">
            {{ $settings->support_email }}
          </a>
        @endif

        @if($settings?->phone)
          <br>
          Call us at
          <a href="tel:{{ $settings->phone }}">
            {{ $settings->phone }}
          </a>
        @endif
      </p>
    </div>

    {{-- Footer --}}
    <div class="email-footer">

      <span class="brand-footer">
        {{ $settings->site_name }}
      </span>

      <p style="margin-top:8px;">
        © {{ date('Y') }}
        {{ $settings->site_name }}.
        All rights reserved.
      </p>

    </div>

  </div>

</body>

</html>