@include('admin.top-header')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  /* ── Design Tokens ──────────────────────────────────────────── */
  :root {
    --bg: #f1f2f4;
    --surface: #ffffff;
    --border: #e3e5e8;
    --text-primary: #202223;
    --text-secondary: #6d7175;
    --text-hint: #8c9196;
    --accent: #303d89;
    /* Shopify indigo-navy */
    --accent-light: #f0f1fc;
    --green: #007a5e;
    --green-bg: #e3f1ec;
    --amber: #916a00;
    --amber-bg: #fff5cc;
    --blue: #0069d9;
    --blue-bg: #e8f2ff;
    --red: #b22222;
    --red-bg: #fce8e8;
    --radius-sm: 8px;
    --radius-md: 12px;
    --shadow-card: 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 1px var(--border);
    --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  }

  /* ── Reset for content area ─────────────────────────────────── */
  .content-area * {
    box-sizing: border-box;
  }

  .content-area {
    background: var(--bg);
    padding: 24px 28px;
    min-height: 100vh;
    font-family: var(--font);
    color: var(--text-primary);
  }

  /* ── Page header ────────────────────────────────────────────── */
  .dash-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }

  .dash-page-header h1 {
    font-size: 20px;
    font-weight: 650;
    color: var(--text-primary) !important;
    margin: 0;
  }

  .dash-page-header .dash-meta {
    font-size: 13px;
    color: var(--text-secondary);
    margin-top: 2px;
  }

  .dash-date-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 7px 13px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
  }

  /* ── Surface card ───────────────────────────────────────────── */
  .cardx {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 20px;
    box-shadow: var(--shadow-card);
    height: 100%;
    transition: box-shadow .18s;
  }

  .cardx:hover {
    box-shadow: 0 3px 10px rgba(0, 0, 0, .1), 0 0 0 1px var(--border);
  }

  .cardx h1,
  .cardx h2,
  .cardx h3,
  .cardx h4,
  .cardx h5,
  .cardx h6,
  .cardx p,
  .cardx td,
  .cardx th,
  .cardx li {
    color: var(--text-primary) !important;
  }

  .cardx h5 {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .02em;
    text-transform: uppercase;
    color: var(--text-secondary) !important;
    margin-bottom: 16px;
  }

  /* ── KPI cards ──────────────────────────────────────────────── */
  .kpi-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 20px 20px 18px;
    box-shadow: var(--shadow-card);
    position: relative;
    height: 100%;
    transition: box-shadow .18s;
  }

  .kpi-card:hover {
    box-shadow: 0 3px 10px rgba(0, 0, 0, .1), 0 0 0 1px var(--border);
  }

  .kpi-label {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 6px;
  }

  .kpi-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary) !important;
    line-height: 1.1;
    margin-bottom: 10px;
  }

  .kpi-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 20px;
  }

  .kpi-badge.up {
    background: var(--green-bg);
    color: var(--green);
  }

  .kpi-badge.warn {
    background: var(--amber-bg);
    color: var(--amber);
  }

  .kpi-badge.info {
    background: var(--blue-bg);
    color: var(--blue);
  }

  .kpi-icon {
    position: absolute;
    top: 18px;
    right: 18px;
    width: 36px;
    height: 36px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    opacity: .85;
  }

  .kpi-icon.purple {
    background: #ede9fe;
    color: #6d28d9;
  }

  .kpi-icon.green {
    background: var(--green-bg);
    color: var(--green);
  }

  .kpi-icon.blue {
    background: var(--blue-bg);
    color: var(--blue);
  }

  .kpi-icon.amber {
    background: var(--amber-bg);
    color: var(--amber);
  }

  .kpi-divider {
    height: 1px;
    background: var(--border);
    margin: 14px -20px;
  }

  .kpi-sub {
    font-size: 12px;
    color: var(--text-hint);
  }

  /* ── Revenue banner ─────────────────────────────────────────── */
  .revenue-banner {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 22px 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: var(--shadow-card);
  }

  .revenue-banner .greet {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 2px;
  }

  .revenue-banner .sub {
    font-size: 13px;
    color: var(--text-secondary);
  }

  .revenue-banner .rev-num {
    text-align: right;
  }

  .revenue-banner .rev-num span {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-primary);
    display: block;
    line-height: 1.1;
  }

  .revenue-banner .rev-num small {
    font-size: 12px;
    color: var(--text-hint);
  }

  /* ── Progress bars ──────────────────────────────────────────── */
  .progress {
    height: 6px;
    border-radius: 10px;
    background: var(--bg);
    overflow: hidden;
  }

  .progress-bar {
    border-radius: 10px;
  }

  .progress-row {
    margin-bottom: 14px;
  }

  .progress-row:last-child {
    margin-bottom: 0;
  }

  .progress-label {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    margin-bottom: 5px;
  }

  .progress-label span:first-child {
    color: var(--text-secondary);
    font-weight: 500;
  }

  .progress-label span:last-child {
    color: var(--text-primary);
    font-weight: 600;
  }

  /* ── Table ──────────────────────────────────────────────────── */
  .dash-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .dash-table thead th {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--text-hint) !important;
    padding: 0 12px 10px;
    border-bottom: 1px solid var(--border);
    text-align: left;
  }

  .dash-table tbody tr {
    border-bottom: 1px solid var(--bg);
    transition: background .12s;
  }

  .dash-table tbody tr:hover {
    background: var(--bg);
  }

  .dash-table tbody tr:last-child {
    border-bottom: none;
  }

  .dash-table tbody td {
    padding: 11px 12px;
    color: var(--text-primary) !important;
    vertical-align: middle;
  }

  /* ── Status badges ──────────────────────────────────────────── */
  .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
  }

  .status-pill::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    display: inline-block;
  }

  .status-pill.delivered {
    background: var(--green-bg);
    color: var(--green);
  }

  .status-pill.delivered::before {
    background: var(--green);
  }

  .status-pill.pending {
    background: var(--amber-bg);
    color: var(--amber);
  }

  .status-pill.pending::before {
    background: var(--amber);
  }

  .status-pill.processing {
    background: var(--blue-bg);
    color: var(--blue);
  }

  .status-pill.processing::before {
    background: var(--blue);
  }

  .status-pill.cancelled,
  .status-pill.returned {
    background: var(--red-bg);
    color: var(--red);
  }

  .status-pill.cancelled::before,
  .status-pill.returned::before {
    background: var(--red);
  }

  /* ── Top products list ──────────────────────────────────────── */
  .product-rank-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--bg);
    font-size: 13px;
  }

  .product-rank-item:last-child {
    border-bottom: none;
  }

  .product-rank-item .rank {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-secondary);
    flex-shrink: 0;
    margin-right: 10px;
  }

  .product-rank-item .name {
    flex: 1;
    font-weight: 500;
    color: var(--text-primary);
  }

  .product-rank-item .qty {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
    background: var(--bg);
    padding: 2px 8px;
    border-radius: 6px;
  }

  /* ── Section label ──────────────────────────────────────────── */
  .section-label {
    font-size: 16px;
    font-weight: 650;
    color: var(--text-primary);
    margin-bottom: 14px;
  }

  /* ── Chart wrapper ──────────────────────────────────────────── */
  .chart-wrap {
    position: relative;
    height: 220px;
  }

  @media (max-width: 768px) {
    .content-area {
      padding: 16px;
    }

    .revenue-banner {
      padding: 16px;
    }

    .kpi-value {
      font-size: 24px;
    }
  }
</style>

<div class="main-section">
  @include('admin.header')

  <div class="container-fluid">
    <div class="content-area">

      <!-- Page header -->
      <div class="dash-page-header">
        <div>
          <h1>Overview</h1>
          <div class="dash-meta">Welcome back, {{ auth()->user()->name }}</div>
        </div>
        <div class="dash-date-badge">
          <i class="fa fa-calendar-alt" style="color:var(--text-hint)"></i>
          {{ now()->format('jS M, Y') }}
          <i class="fa fa-chevron-down" style="font-size:10px;color:var(--text-hint)"></i>
        </div>
      </div>


      <!-- KPI Row -->
      <div class="row g-3 mb-3">
        <div class="col-md-3 col-sm-6">
          <div class="kpi-card">
            <div class="kpi-icon purple"><i class="fa fa-box"></i></div>
            <div class="kpi-label">Total Products</div>
            <div class="kpi-value">{{ $stats['products'] }}</div>
            <span class="kpi-badge up"><i class="fa fa-circle" style="font-size:6px"></i>
              {{ $activeCounts['products'] }} active</span>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="kpi-card">
            <div class="kpi-icon blue"><i class="fa fa-boxes-stacked"></i></div>
            <div class="kpi-label">Categories</div>
            <div class="kpi-value">{{ $stats['categories'] }}</div>
            <span class="kpi-badge info">{{ $stats['brands'] }} brands</span>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="kpi-card">
            <div class="kpi-icon green"><i class="fa fa-images"></i></div>
            <div class="kpi-label">Gallery Images</div>
            <div class="kpi-value">{{ $stats['galleryImages'] }}</div>
            <span class="kpi-badge up">{{ $activeCounts['gallery'] }} active</span>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="kpi-card">
            <div class="kpi-icon amber"><i class="fa fa-comments"></i></div>
            <div class="kpi-label">Testimonials</div>
            <div class="kpi-value">{{ $stats['testimonials'] }}</div>
            <span class="kpi-badge warn">{{ $activeCounts['testimonials'] }} published</span>
          </div>
        </div>
      </div>

      <!-- Content snapshot banner (replaces revenue banner) -->
      <div class="revenue-banner">
        <div>
          <div class="greet">Content Snapshot</div>
          <div class="sub">{{ $stats['services'] }} homepage products · {{ $stats['sliders'] }} slides ·
            {{ $stats['clients'] }} client logos
          </div>
        </div>
        <div class="rev-num">
          <span>{{ $stats['products'] + $stats['categories'] + $stats['galleryImages'] + $stats['testimonials'] + $stats['services'] + $stats['sliders'] + $stats['clients'] + $stats['brands'] }}</span>
          <small>Total content items across the site</small>
        </div>
      </div>

      <div class="row g-3 mb-3">
        <!-- Products added, last 6 months -->
        <div class="col-lg-7">
          <div class="cardx">
            <h5>Products Added — Last 6 Months</h5>
            <div class="chart-wrap">
              <canvas id="productsChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Publish status by module -->
        <div class="col-lg-5">
          <div class="cardx">
            <h5>Publish Status</h5>
            @php
              $rows = [
                ['label' => 'Products', 'active' => $activeCounts['products'], 'total' => $stats['products'], 'color' => 'var(--accent)'],
                ['label' => 'Categories', 'active' => $activeCounts['categories'], 'total' => $stats['categories'], 'color' => 'var(--blue)'],
                ['label' => 'Gallery', 'active' => $activeCounts['gallery'], 'total' => $stats['galleryImages'], 'color' => 'var(--green)'],
                ['label' => 'Testimonials', 'active' => $activeCounts['testimonials'], 'total' => $stats['testimonials'], 'color' => 'var(--amber)'],
              ];
            @endphp
            @foreach($rows as $row)
              @php $pct = $row['total'] > 0 ? round(($row['active'] / $row['total']) * 100) : 0; @endphp
              <div class="progress-row">
                <div class="progress-label">
                  <span>{{ $row['label'] }}</span>
                  <span>{{ $row['active'] }} / {{ $row['total'] }}</span>
                </div>
                <div class="progress">
                  <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $row['color'] }}"></div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="row g-3">
        <!-- Recent testimonials -->
        <div class="col-lg-7">
          <div class="cardx">
            <h5>Recent Testimonials</h5>
            <table class="dash-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Testimonial</th>
                  <th>Status</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentTestimonials as $t)
                  <tr>
                    <td>{{ $t->title ?: '—' }}</td>
                    <td>{{ Str::limit(strip_tags($t->content), 50) }}</td>
                    <td>
                      <span class="status-pill {{ $t->status == 'active' ? 'delivered' : 'cancelled' }}">
                        {{ ucfirst($t->status) }}
                      </span>
                    </td>
                    <td>{{ $t->created_at->format('d M Y') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" style="text-align:center; color:var(--text-hint); padding:24px 0;">No testimonials yet
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Top categories -->
        <div class="col-lg-5">
          <div class="cardx">
            <h5>Top Categories by Product Count</h5>
            @forelse($topCategories as $i => $cat)
              <div class="product-rank-item">
                <div class="rank">{{ $i + 1 }}</div>
                <div class="name">{{ $cat->title }}</div>
                <div class="qty">{{ $cat->details_count }}</div>
              </div>
            @empty
              <div style="color:var(--text-hint); font-size:13px; padding:12px 0;">No categories yet</div>
            @endforelse
          </div>
        </div>
      </div>

    </div><!-- /content-area -->
  </div><!-- /container-fluid -->
</div><!-- /main-section -->

@include('admin.footer')

<script>
  const productsChart = new Chart(document.getElementById('productsChart'), {
    type: 'bar',
    data: {
      labels: {!! json_encode($monthly->pluck('label')) !!},
      datasets: [{
        label: 'Products Added',
        data: {!! json_encode($monthly->pluck('total')) !!},
        backgroundColor: '#303d89',
        borderRadius: 6,
        maxBarThickness: 40
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f2f4' } },
        x: { grid: { display: false } }
      }
    }
  });
</script>