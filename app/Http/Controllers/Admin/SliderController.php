<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $query = Slider::query();

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

        $sliders = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();

        return view('admin.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'content' => 'nullable|string',
        ]);

        $data = $request->only('title', 'content');
        $data['image'] = $this->storeCompressedImage($request->file('image'));
        $data['status'] = 'active';

        Slider::create($data);

        return redirect()->route('admin.slider.index')->with('success', 'Slider added successfully.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'content' => 'nullable|string',
        ]);

        $data = $request->only('title', 'content');

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $this->storeCompressedImage($request->file('image'));
        }

        $slider->update($data);

        return redirect()->route('admin.slider.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();

        return response()->json(['message' => 'Slider deleted successfully.']);
    }

    /**
     * Activate / Block toggle — replaces the old bulk-action workflow.
     */
    public function toggleStatus(Slider $slider)
    {
        $slider->status = $slider->status === 'active' ? 'block' : 'active';
        $slider->save();

        return response()->json([
            'message' => 'Status updated successfully.',
            'status' => $slider->status,
        ]);
    }

    /**
     * Compress + convert to WebP (ImageManager directly, no Facade dependency).
     */
    private function storeCompressedImage($file): string
    {
        $filename = 'sliders/' . uniqid() . '.webp';

        $manager = ImageManager::usingDriver(Driver::class);

        $encoded = $manager->decode($file->getRealPath())
            ->encode(new WebpEncoder(quality: 80));

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}