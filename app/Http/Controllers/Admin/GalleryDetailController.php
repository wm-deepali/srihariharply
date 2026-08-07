<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryDetailController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryDetail::with('gallery');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('gallery') && $request->gallery !== 'all') {
            $query->where('gallery_id', $request->gallery);
        }

        $sortBy    = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['id', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $images    = $query->orderBy($sortBy, $sortOrder)->paginate(24)->withQueryString();
        $galleries = Gallery::where('status', 'active')->orderBy('title')->get();

        return view('admin.gallery-details.index', compact('images', 'galleries'));
    }

    public function create()
    {
        $galleries = Gallery::where('status', 'active')->orderBy('title')->get();

        return view('admin.gallery-details.create', compact('galleries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gallery_id' => 'required|exists:galleries,id',
            'image'      => 'required|array|min:1',
            'image.*'    => 'image|max:2048',
        ]);

        foreach ($request->file('image') as $file) {
            GalleryDetail::create([
                'gallery_id' => $request->gallery_id,
                'image'      => $file->store('gallery-details', 'public'),
                'status'     => 'active',
            ]);
        }

        return redirect()->route('admin.gallery-details.index')->with('success', 'Image(s) added successfully.');
    }

    public function edit(GalleryDetail $galleryDetail)
    {
        $galleries = Gallery::where('status', 'active')->orderBy('title')->get();

        return view('admin.gallery-details.edit', ['image' => $galleryDetail, 'galleries' => $galleries]);
    }

    public function update(Request $request, GalleryDetail $galleryDetail)
    {
        $request->validate([
            'gallery_id' => 'required|exists:galleries,id',
            'image'      => 'nullable|image|max:2048',
        ]);

        $data = $request->only('gallery_id');

        if ($request->hasFile('image')) {
            if ($galleryDetail->image) {
                Storage::disk('public')->delete($galleryDetail->image);
            }
            $data['image'] = $request->file('image')->store('gallery-details', 'public');
        }

        $galleryDetail->update($data);

        return redirect()->route('admin.gallery-details.index')->with('success', 'Image updated successfully.');
    }

    public function destroy(GalleryDetail $galleryDetail)
    {
        if ($galleryDetail->image) {
            Storage::disk('public')->delete($galleryDetail->image);
        }

        $galleryDetail->delete();

        return response()->json(['message' => 'Image deleted successfully.']);
    }
}