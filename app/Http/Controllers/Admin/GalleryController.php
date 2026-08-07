<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sortBy    = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['id', 'title', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $galleries = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();

        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);

        Gallery::create(['title' => $request->title, 'status' => 'active']);

        return redirect()->route('admin.gallery.index')->with('success', 'Image category added successfully.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate(['title' => 'required|string|max:255']);

        $gallery->update($request->only('title'));

        return redirect()->route('admin.gallery.index')->with('success', 'Image category updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return response()->json(['message' => 'Image category deleted successfully.']);
    }
}