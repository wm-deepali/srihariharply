@include('admin.top-header')

<div class="main-section">
    @include('admin.header')

    <style>
    :root {
        --bg:#f1f2f4; --surface:#ffffff; --border:#e3e5e8; --text-primary:#202223; --text-secondary:#6d7175;
        --text-hint:#8c9196; --accent:#303d89; --green:#007a5e; --green-bg:#e3f1ec;
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
    .crumb span { margin: 0 5px; }
    .btn-primary-dash { display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff !important; border: none; border-radius: var(--radius-sm); padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none !important; font-family: var(--font); box-shadow: 0 1px 3px rgba(48,61,137,.25); }
    .btn-primary-dash:hover { background: #252f70; }
    .os-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-card); overflow: hidden; }
    .filter-bar { padding: 16px 20px; border-bottom: 1px solid var(--border); background: var(--surface); }
    .filter-row { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label { font-size: 12px; font-weight: 600; color: var(--text-secondary); letter-spacing: .03em; text-transform: uppercase; }
    .filter-control { height: 36px; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 11px; font-size: 13px; background: var(--surface); outline: none; font-family: var(--font); min-width: 160px; }
    .filter-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(48,61,137,.12); }
    .filter-actions { display: flex; gap: 8px; align-items: center; }
    .btn-filter-search { height: 36px; display: inline-flex; align-items: center; gap: 6px; background: var(--accent); color: #fff; border: none; border-radius: var(--radius-sm); padding: 0 16px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: var(--font); }
    .btn-filter-search:hover { background: #252f70; }
    .btn-filter-reset { height: 36px; display: inline-flex; align-items: center; gap: 6px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0 14px; font-size: 13px; cursor: pointer; text-decoration: none; font-family: var(--font); color: var(--text-primary); }
    .btn-filter-reset:hover { background: var(--bg); }
    .img-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px; padding: 20px; }
    .img-card { border: 1px solid var(--border); border-radius: var(--radius-sm); overflow: hidden; background: var(--surface); }
    .img-card img { width: 100%; height: 120px; object-fit: cover; display: block; }
    .img-card-body { padding: 8px 10px; display: flex; align-items: center; justify-content: space-between; gap: 6px; }
    .id-chip { display: inline-block; background: var(--bg); color: var(--text-secondary); font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 6px; font-family: monospace; }
    .os-sub { font-size: 11.5px; color: var(--text-hint); }
    .pill { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px; border: none; }
    .pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .pill-active { background: var(--green-bg); color: var(--green); } .pill-active::before { background: var(--green); }
    .pill-inactive { background: var(--red-bg); color: var(--red); } .pill-inactive::before { background: var(--red); }
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--surface); color: var(--text-secondary); font-size: 11px; cursor: pointer; text-decoration: none; }
    .action-btn:hover { background: var(--bg); color: var(--text-primary); }
    .action-btn-danger:hover { background: var(--red-bg); border-color: #f5c6c6; color: var(--red); }
    .empty-state { text-align: center; padding: 64px 20px; grid-column: 1/-1; }
    .empty-state .empty-icon { width: 56px; height: 56px; border-radius: 50%; background: var(--bg); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; color: var(--text-hint); margin-bottom: 14px; }
    .empty-state p { font-size: 14px; color: var(--text-secondary); margin: 6px 0 16px; }
    .os-pagination { padding: 14px 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; }
    @media(max-width:768px) { .os-page { padding: 16px; } .filter-row { flex-direction: column; } .filter-control { min-width: 100%; } }
    </style>

    <div class="app-content content container-fluid">
        <div class="os-page">

            <div class="os-page-header">
                <div>
                    <h1>Picture Gallery — Image Gallery</h1>
                    <div class="crumb"><a href="{{ route('admin.dashboard') }}">Dashboard</a><span>›</span>Image Gallery</div>
                </div>
                <a href="{{ route('admin.gallery-details.create') }}" class="btn-primary-dash"><i class="fa fa-plus"></i> Add Image Gallery</a>
            </div>

            <div class="os-card">
                <div class="filter-bar">
                    <form method="GET">
                        <div class="filter-row">
                            <div class="filter-group">
                                <label>Category</label>
                                <select name="gallery" class="filter-control" onchange="this.form.submit()">
                                    <option value="all" {{ request('gallery','all')=='all'?'selected':'' }}>All</option>
                                    @foreach($galleries as $g)
                                        <option value="{{ $g->id }}" {{ request('gallery')==$g->id?'selected':'' }}>{{ $g->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Status</label>
                                <select name="status" class="filter-control" onchange="this.form.submit()">
                                    <option value="all" {{ request('status','all')=='all'?'selected':'' }}>All</option>
                                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                                    <option value="block" {{ request('status')=='block'?'selected':'' }}>Blocked</option>
                                </select>
                            </div>
                            <div class="filter-actions">
                                <button type="submit" class="btn-filter-search"><i class="fa fa-search"></i> Filter</button>
                                <a href="{{ route('admin.gallery-details.index') }}" class="btn-filter-reset"><i class="fa fa-refresh"></i> Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="img-grid">
                    @forelse($images as $item)
                        <div class="img-card" id="row{{ $item->id }}">
                            @if($item->image)
                                <img src="{{ $item->image_url }}">
                            @else
                                <div style="height:120px;display:flex;align-items:center;justify-content:center;background:var(--bg);color:var(--text-hint);"><i class="fa fa-image"></i></div>
                            @endif
                            <div class="img-card-body">
                                <div>
                                    <span class="id-chip">{{ $item->id }}</span>
                                    <div class="os-sub">{{ $item->gallery->title ?? '—' }}</div>
                                </div>
                                <div style="display:flex;gap:5px;align-items:center;">
                                    <span class="pill {{ $item->status=='active'?'pill-active':'pill-inactive' }}">{{ ucfirst($item->status) }}</span>
                                    <a href="{{ route('admin.gallery-details.edit', $item->id) }}" class="action-btn"><i class="fa fa-pencil"></i></a>
                                    <button class="action-btn action-btn-danger" onclick="deleteItem({{ $item->id }})"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fa fa-images"></i></div>
                            <strong>No images found</strong>
                            <p>Add images to a category to get started.</p>
                            <a href="{{ route('admin.gallery-details.create') }}" class="btn-primary-dash"><i class="fa fa-plus"></i> Add Image Gallery</a>
                        </div>
                    @endforelse
                </div>

                <div class="os-pagination">{{ $images->links('pagination::bootstrap-4') }}</div>
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
                url: "{{ url('admin/gallery-details') }}/" + id, type: 'DELETE', data: { _token: "{{ csrf_token() }}" },
                beforeSend: function () { Swal.showLoading(); },
                success: function (res) { Swal.fire('Deleted!', res.message, 'success'); $("#row"+id).fadeOut(400, function(){ $(this).remove(); }); },
                error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
            });
        }
    });
}
</script>