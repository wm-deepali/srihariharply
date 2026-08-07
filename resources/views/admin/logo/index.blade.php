@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    <style>
    :root {
        --bg:            #f1f2f4;
        --surface:       #ffffff;
        --border:        #e3e5e8;
        --text-primary:  #202223;
        --text-secondary:#6d7175;
        --text-hint:     #8c9196;
        --accent:        #303d89;
        --accent-light:  #f0f1fc;
        --green:         #007a5e;
        --green-bg:      #e3f1ec;
        --red:           #b22222;
        --red-bg:        #fce8e8;
        --radius-sm:     8px;
        --radius-md:     12px;
        --shadow-card:   0 1px 3px rgba(0,0,0,.08), 0 0 0 1px var(--border);
        --font:          'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .logo-page { background: var(--bg); padding: 24px 28px; min-height: 100vh; font-family: var(--font); color: var(--text-primary); }
    .logo-page * { box-sizing: border-box; }

    .logo-page-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .logo-page-header h1 { font-size: 20px; font-weight: 650; color: var(--text-primary); margin: 0; }
    .crumb { font-size: 12.5px; color: var(--text-hint); margin-top: 3px; }
    .crumb a { color: var(--accent); text-decoration: none; }
    .crumb a:hover { text-decoration: underline; }
    .crumb span { margin: 0 5px; }

    .btn-primary-dash {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--accent); color: #fff !important; border: none;
        border-radius: var(--radius-sm); padding: 8px 16px;
        font-size: 13px; font-weight: 600; cursor: pointer;
        text-decoration: none !important; font-family: var(--font);
        transition: background .15s; box-shadow: 0 1px 3px rgba(48,61,137,.25);
    }
    .btn-primary-dash:hover { background: #252f70; }

    .logo-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); overflow: hidden; }

    .filter-bar { padding: 16px 20px; border-bottom: 1px solid var(--border); background: var(--surface); }
    .filter-row { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label { font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; }
    .filter-control {
        height: 36px; border: 1px solid var(--border); border-radius: var(--radius-sm);
        padding: 0 11px; font-size: 13px; color: var(--text-primary); background: var(--surface);
        outline: none; transition: border-color .15s, box-shadow .15s; font-family: var(--font); min-width: 160px;
    }
    .filter-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }
    .filter-control-wide { min-width: 220px; }
    .filter-actions { display: flex; gap: 8px; align-items: center; }

    .btn-filter-search {
        height: 36px; display: inline-flex; align-items: center; gap: 6px;
        background: var(--accent); color: #fff; border: none; border-radius: var(--radius-sm);
        padding: 0 16px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: var(--font);
        transition: background .15s;
    }
    .btn-filter-search:hover { background: #252f70; }
    .btn-filter-reset {
        height: 36px; display: inline-flex; align-items: center; gap: 6px;
        background: var(--surface); color: var(--text-primary); border: 1px solid var(--border);
        border-radius: var(--radius-sm); padding: 0 14px; font-size: 13px; font-weight: 500;
        cursor: pointer; text-decoration: none; font-family: var(--font); transition: background .15s;
    }
    .btn-filter-reset:hover { background: var(--bg); }

    .logo-table-wrap { overflow-x: auto; }
    .logo-table { width: 100%; border-collapse: collapse; font-size: 13px; font-family: var(--font); }
    .logo-table thead th {
        font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase;
        color: var(--text-hint); padding: 10px 16px; border-bottom: 1px solid var(--border);
        background: #fafafa; text-align: left; white-space: nowrap;
    }
    .logo-table tbody tr { border-bottom: 1px solid var(--border); transition: background .1s; }
    .logo-table tbody tr:last-child { border-bottom: none; }
    .logo-table tbody tr:hover { background: #fafbfc; }
    .logo-table tbody td { padding: 12px 16px; color: var(--text-primary); vertical-align: middle; }

    .sort-link { color: var(--text-hint); text-decoration: none; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; display: inline-flex; align-items: center; gap: 4px; transition: color .12s; }
    .sort-link:hover { color: var(--text-primary); text-decoration: none; }
    .sort-link .fa-sort { opacity: .4; }
    .sort-link .fa-sort-up, .sort-link .fa-sort-down { color: var(--accent); }

    .logo-img { width: 90px; height: 40px; border-radius: var(--radius-sm); object-fit: contain; border: 1px solid var(--border); background: #fafafa; }
    .logo-img-placeholder { width: 90px; height: 40px; border-radius: var(--radius-sm); background: var(--bg); display: flex; align-items: center; justify-content: center; color: var(--text-hint); font-size: 16px; border: 1px solid var(--border); }

    .logo-title { font-weight: 600; color: var(--text-primary); font-size: 13px; }
    .id-chip { display: inline-block; background: var(--bg); color: var(--text-secondary); font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 6px; font-family: 'SF Mono','Fira Code',monospace; }

    .pill { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 600; padding: 3px 9px; border-radius: 20px; border: none; cursor: pointer; font-family: var(--font); }
    .pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .pill-active   { background: var(--green-bg); color: var(--green); }
    .pill-active::before { background: var(--green); }
    .pill-inactive { background: var(--red-bg); color: var(--red); }
    .pill-inactive::before { background: var(--red); }

    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text-secondary); font-size: 12px; cursor: pointer; transition: all .12s; text-decoration: none; }
    .action-btn:hover { background: var(--bg); color: var(--text-primary); }
    .action-btn-danger:hover { background: var(--red-bg); border-color: #f5c6c6; color: var(--red); }

    .empty-state { text-align: center; padding: 64px 20px; }
    .empty-state .empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--bg); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; color: var(--text-hint); margin-bottom: 14px; }
    .empty-state p { font-size: 14px; color: var(--text-secondary); margin: 6px 0 16px; }

    .logo-pagination { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; background: var(--surface); }

    @media(max-width:768px) { .logo-page { padding: 16px; } .filter-row { flex-direction: column; } .filter-control { min-width: 100%; } }
    </style>

    <div class="app-content content container-fluid">
        <div class="logo-page">

            <div class="logo-page-header">
                <div>
                    <h1>Logo Management</h1>
                    <div class="crumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <span>›</span>
                        Logo Management
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.logo.create') }}" class="btn-primary-dash">
                        <i class="fa fa-plus"></i> Add Logo
                    </a>
                </div>
            </div>

            <div class="logo-card">

                <div class="filter-bar">
                    <form method="GET">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label>Status</label>
                                <select name="status" class="filter-control" onchange="this.form.submit()">
                                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="block" {{ request('status') == 'block' ? 'selected' : '' }}>Blocked</option>
                                </select>
                            </div>
                            <div class="filter-group" style="flex:1">
                                <label>Search</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="filter-control filter-control-wide" placeholder="Search logo title…">
                            </div>
                            <div class="filter-actions">
                                <button type="submit" class="btn-filter-search">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                <a href="{{ route('admin.logo.index') }}" class="btn-filter-reset">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="logo-table-wrap">

                    @php
                        function logoSortUrl($column) {
                            $direction = request('sort_by') == $column && request('sort_order') == 'asc' ? 'desc' : 'asc';
                            return request()->fullUrlWithQuery(['sort_by' => $column, 'sort_order' => $direction]);
                        }
                        function logoSortIcon($column) {
                            if (request('sort_by') != $column) return '<i class="fa fa-sort"></i>';
                            return request('sort_order') == 'asc'
                                ? '<i class="fa fa-sort-up" style="color:var(--accent)"></i>'
                                : '<i class="fa fa-sort-down" style="color:var(--accent)"></i>';
                        }
                    @endphp

                    <table class="logo-table">
                        <thead>
                            <tr>
                                <th><a href="{{ logoSortUrl('id') }}" class="sort-link">ID {!! logoSortIcon('id') !!}</a></th>
                                <th>Image</th>
                                <th><a href="{{ logoSortUrl('title') }}" class="sort-link">Title {!! logoSortIcon('title') !!}</a></th>
                                <th><a href="{{ logoSortUrl('status') }}" class="sort-link">Status {!! logoSortIcon('status') !!}</a></th>
                                <th style="width:120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logos as $item)
                                <tr id="row{{ $item->id }}">
                                    <td><span class="id-chip">{{ $item->id }}</span></td>
                                    <td>
                                        @if($item->image)
                                            <img src="{{ $item->image_url }}" class="logo-img" alt="{{ $item->title }}">
                                        @else
                                            <div class="logo-img-placeholder"><i class="fa fa-image"></i></div>
                                        @endif
                                    </td>
                                    <td><span class="logo-title">{{ $item->title ?: '—' }}</span></td>
                                    <td>
                                        <button type="button"
                                            class="pill {{ $item->status == 'active' ? 'pill-active' : 'pill-inactive' }}"
                                            onclick="toggleStatus({{ $item->id }})" title="Click to toggle">
                                            {{ ucfirst($item->status) }}
                                        </button>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px">
                                            <a href="{{ route('admin.logo.edit', $item->id) }}" class="action-btn" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <button class="action-btn action-btn-danger" onclick="deleteLogo({{ $item->id }})" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fa fa-image"></i></div>
                                            <strong style="font-size:14px;color:var(--text-primary)">No logos found</strong>
                                            <p>Add a new logo to get started.</p>
                                            <a href="{{ route('admin.logo.create') }}" class="btn-primary-dash">
                                                <i class="fa fa-plus"></i> Add Logo
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="logo-pagination">
                    {{ $logos->links('pagination::bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>
</div>

@include('admin.footer')

<script>
function deleteLogo(id) {
    Swal.fire({
        title: 'Delete Logo?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b22222',
        cancelButtonColor: '#6d7175',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('admin/logo') }}/" + id,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                beforeSend: function () { Swal.showLoading(); },
                success: function (res) {
                    Swal.fire('Deleted!', res.message, 'success');
                    $("#row" + id).fadeOut(400, function () { $(this).remove(); });
                },
                error: function () {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }
    });
}

function toggleStatus(id) {
    $.ajax({
        url: "{{ url('admin/logo') }}/" + id + "/toggle-status",
        type: 'POST',
        data: { _token: "{{ csrf_token() }}" },
        success: function (res) {
            Swal.fire({ icon: 'success', title: res.message, timer: 1200, showConfirmButton: false });
            setTimeout(() => location.reload(), 600);
        },
        error: function () {
            Swal.fire('Error!', 'Something went wrong.', 'error');
        }
    });
}
</script>