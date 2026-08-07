<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class LogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Logo::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['id', 'title', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $logos = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();

        return view('admin.logo.index', compact('logos'));
    }

    public function create()
    {
        return view('admin.logo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only('title');
        $data['image'] = $this->storeCompressedImage($request->file('image'));
        $data['status'] = 'active';

        Logo::create($data);

        return redirect()->route('admin.logo.index')->with('success', 'Logo added successfully.');
    }

    public function edit(Logo $logo)
    {
        return view('admin.logo.edit', compact('logo'));
    }

    public function update(Request $request, Logo $logo)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only('title');

        if ($request->hasFile('image')) {
            if ($logo->image) {
                Storage::disk('public')->delete($logo->image);
            }
            $data['image'] = $this->storeCompressedImage($request->file('image'));
        }

        $logo->update($data);

        return redirect()->route('admin.logo.index')->with('success', 'Logo updated successfully.');
    }

    public function destroy(Logo $logo)
    {
        if ($logo->image) {
            Storage::disk('public')->delete($logo->image);
        }
        $logo->delete();

        return response()->json(['message' => 'Logo deleted successfully.']);
    }

    /**
     * Activate / Block toggle — replaces the old bulk-action workflow.
     */
    public function toggleStatus(Logo $logo)
    {
        $logo->status = $logo->status === 'active' ? 'block' : 'active';
        $logo->save();

        return response()->json([
            'message' => 'Status updated successfully.',
            'status' => $logo->status,
        ]);
    }

    /**
     * Compress + convert to WebP.
     * Uses ImageManager directly (no Facade dependency) — works as long as
     * `composer require intervention/image-laravel` has been run.
     */
    private function storeCompressedImage($file): string
    {
        $filename = 'logos/' . uniqid() . '.webp';

        $manager = ImageManager::usingDriver(Driver::class);

        $encoded = $manager->decode($file->getRealPath())
            ->encode(new WebpEncoder(quality: 80));

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}