@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    <style>
    :root {
        --bg:#f1f2f4; --surface:#ffffff; --border:#e3e5e8; --text-primary:#202223; --text-secondary:#6d7175;
        --text-hint:#8c9196; --accent:#303d89; --accent-light:#f0f1fc; --green:#007a5e; --green-bg:#e3f1ec;
        --red:#b22222; --red-bg:#fce8e8; --radius-sm:8px; --radius-md:12px;
        --shadow-card:0 1px 3px rgba(0,0,0,.08), 0 0 0 1px var(--border);
        --font:'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    .os-page { background: var(--bg); padding: 24px 28px; min-height: 100vh; font-family: var(--font); color: var(--text-primary); }
    .os-page * { box-sizing: border-box; }
    .os-page-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .os-page-header h1 { font-size: 20px; font-weight: 650; margin: 0; }
    .crumb { font-size: 12.5px; color: var(--text-hint); margin-top: 3px; }
    .crumb a { color: var(--accent); text-decoration: none; }
    .crumb a:hover { text-decoration: underline; }
    .crumb span { margin: 0 5px; }
    .btn-primary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff !important; border: none; border-radius: var(--radius-sm); padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none !important; font-family: var(--font); transition: background .15s; box-shadow: 0 1px 3px rgba(48,61,137,.25); }
    .btn-primary-dash:hover { background: #252f70; }
    .os-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); overflow: hidden; }
    .filter-bar { padding: 16px 20px; border-bottom: 1px solid var(--border); background: var(--surface); }
    .filter-row { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label { font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; }
    .filter-control { height: 36px; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 11px; font-size: 13px; background: var(--surface); outline: none; font-family: var(--font); min-width: 160px; }
    .filter-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }
    .filter-control-wide { min-width: 220px; }
    .filter-actions { display: flex; gap: 8px; align-items: center; }
    .btn-filter-search { height: 36px; display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff; border: none; border-radius: var(--radius-sm); padding: 0 16px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: var(--font); }
    .btn-filter-search:hover { background: #252f70; }
    .btn-filter-reset { height: 36px; display: inline-flex; align-items: center; gap: 6px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 14px; font-size: 13px; cursor: pointer; text-decoration: none; font-family: var(--font); color: var(--text-primary); }
    .btn-filter-reset:hover { background: var(--bg); }
    .os-table-wrap { overflow-x: auto; }
    .os-table { width: 100%; border-collapse: collapse; font-size: 13px; font-family: var(--font); }
    .os-table thead th { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--text-hint); padding: 10px 16px; border-bottom: 1px solid var(--border); background: #fafafa; text-align: left; white-space: nowrap; }
    .os-table tbody tr { border-bottom: 1px solid var(--border); }
    .os-table tbody tr:last-child { border-bottom: none; }
    .os-table tbody tr:hover { background: #fafbfc; }
    .os-table tbody td { padding: 12px 16px; vertical-align: middle; }
    .sort-link { color: var(--text-hint); text-decoration: none; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; display: inline-flex; align-items: center; gap: 4px; }
    .sort-link:hover { color: var(--text-primary); }
    .os-title { font-weight: 600; font-size: 13px; }
    .os-excerpt { font-size: 12px; color: var(--text-hint); margin-top: 2px; max-width: 360px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .id-chip { display: inline-block; background: var(--bg); color: var(--text-secondary); font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 6px; font-family: monospace; }
    .pill { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 600; padding: 3px 9px; border-radius: 20px; border: none; cursor: pointer; font-family: var(--font); }
    .pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .pill-active { background: var(--green-bg); color: var(--green); } .pill-active::before { background: var(--green); }
    .pill-inactive { background: var(--red-bg); color: var(--red); } .pill-inactive::before { background: var(--red); }
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text-secondary); font-size: 12px; cursor: pointer; text-decoration: none; }
    .action-btn:hover { background: var(--bg); color: var(--text-primary); }
    .action-btn-danger:hover { background: var(--red-bg); border-color: #f5c6c6; color: var(--red); }
    .empty-state { text-align: center; padding: 64px 20px; }
    .empty-state .empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--bg); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; color: var(--text-hint); margin-bottom: 14px; }
    .empty-state p { font-size: 14px; color: var(--text-secondary); margin: 6px 0 16px; }
    .os-pagination { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; }
    @media(max-width:768px) { .os-page { padding: 16px; } .filter-row { flex-direction: column; } .filter-control { min-width: 100%; } }
    </style>

    <div class="app-content content container-fluid">
        <div class="os-page">

            <div class="os-page-header">
                <div>
                    <h1>Home Page Mgmt — Our Product</h1>
                    <div class="crumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a><span>›</span>Our Product</div>
                </div>
                <a href="{{ route('admin.our-services.create') }}" class="btn-primary-dash"><i class="fa fa-plus"></i> Add Our Product</a>
            </div>

            <div class="os-card">
                <div class="filter-bar">
                    <form method="GET">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label>Status</label>
                                <select name="status" class="filter-control" onchange="this.form.submit()">
                                    <option value="all" {{ request('status','all')=='all'?'selected':'' }}>All</option>
                                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                                    <option value="block" {{ request('status')=='block'?'selected':'' }}>Blocked</option>
                                </select>
                            </div>
                            <div class="filter-group" style="flex:1">
                                <label>Search</label>
                                <input type="text" name="search" value="{{ request('search') }}" class="filter-control filter-control-wide" placeholder="Search title…">
                            </div>
                            <div class="filter-actions">
                                <button type="submit" class="btn-filter-search"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ route('admin.our-services.index') }}" class="btn-filter-reset"><i class="fa fa-refresh"></i> Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="os-table-wrap">
                    @php
                        function osSortUrl($column) {
                            $direction = request('sort_by') == $column && request('sort_order') == 'asc' ? 'desc' : 'asc';
                            return request()->fullUrlWithQuery(['sort_by' => $column, 'sort_order' => $direction]);
                        }
                    @endphp
                    <table class="os-table">
                        <thead>
                            <tr>
                                <th><a href="{{ osSortUrl('id') }}" class="sort-link">ID</a></th>
                                <th><a href="{{ osSortUrl('title') }}" class="sort-link">Title</a></th>
                                <th><a href="{{ osSortUrl('status') }}" class="sort-link">Status</a></th>
                                <th style="width:120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $item)
                                <tr id="row{{ $item->id }}">
                                    <td><span class="id-chip">{{ $item->id }}</span></td>
                                    <td>
                                        <div class="os-title">{{ $item->title ?: '—' }}</div>
                                        <div class="os-excerpt">{{ Str::limit(strip_tags($item->content), 80) }}</div>
                                    </td>
                                    <td>
                                        <button type="button" class="pill {{ $item->status=='active'?'pill-active':'pill-inactive' }}" onclick="toggleStatus({{ $item->id }})">
                                            {{ ucfirst($item->status) }}
                                        </button>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px">
                                            <a href="{{ route('admin.our-services.edit', $item->id) }}" class="action-btn"><i class="fa fa-pencil"></i></a>
                                            <button class="action-btn action-btn-danger" onclick="deleteItem({{ $item->id }})"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa fa-cube"></i></div>
                                            <strong>No products found</strong>
                                            <p>Add a new product to get started.</p>
                                            <a href="{{ route('admin.our-services.create') }}" class="btn-primary-dash"><i class="fa fa-plus"></i> Add Our Product</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="os-pagination">{{ $services->links('pagination::bootstrap-4') }}</div>
            </div>
        </div>
    </div>
</div>

@include('admin.footer')

<script>
function deleteItem(id) {
    Swal.fire({ title: 'Delete?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#b22222', cancelButtonColor: '#6d7175', confirmButtonText: 'Yes, Delete' })
    .then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('admin/our-services') }}/" + id, type: 'DELETE', data: { _token: "{{ csrf_token() }}" },
                beforeSend: function () { Swal.showLoading(); },
                success: function (res) { Swal.fire('Deleted!', res.message, 'success'); $("#row"+id).fadeOut(400, function(){ $(this).remove(); }); },
                error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
            });
        }
    });
}
function toggleStatus(id) {
    $.ajax({
        url: "{{ url('admin/our-services') }}/" + id + "/toggle-status", type: 'POST', data: { _token: "{{ csrf_token() }}" },
        success: function (res) { Swal.fire({ icon:'success', title: res.message, timer: 1200, showConfirmButton: false }); setTimeout(() => location.reload(), 600); },
        error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
    });
}
</script>