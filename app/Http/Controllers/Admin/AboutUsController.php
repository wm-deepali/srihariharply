<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::firstOrCreate(['id' => 1], ['status' => 'active']);

        return view('admin.about-us.index', compact('aboutUs'));
    }

    public function update(Request $request, AboutUs $aboutUs)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'content' => 'nullable|string',
        ]);

        $data = $request->only('title', 'content');

        if ($request->hasFile('image')) {
            // clean up old image + thumb (same as old unlink() calls)
            if ($aboutUs->image) {
                Storage::disk('public')->delete($aboutUs->image);
            }
            if ($aboutUs->thumb) {
                Storage::disk('public')->delete($aboutUs->thumb);
            }

            [$imagePath, $thumbPath] = $this->storeImageWithThumbnail($request->file('image'));
            $data['image'] = $imagePath;
            $data['thumb'] = $thumbPath;
        }

        $aboutUs->update($data);

        return redirect()->route('admin.about-us.index')->with('success', 'About Us updated successfully.');
    }

    /**
     * Store the original image (WebP compressed) + a 430x400 thumbnail.
     * Mirrors the old thumbnail.class.php generateThumbnail($src, $dest, 430, 400) behavior.
     * Intervention Image v4.1 API: ImageManager::usingDriver() + decodePath() for file-path reads.
     *
     * @return array{0: string, 1: string} [$imagePath, $thumbPath]
     */
    private function storeImageWithThumbnail($file): array
    {
        $manager = ImageManager::usingDriver(Driver::class);
        $uid     = uniqid();

        // Original (compressed, original aspect ratio kept)
        $imagePath = 'about-us/' . $uid . '.webp';
        $original  = $manager->decodePath($file->getRealPath())
            ->encode(new WebpEncoder(quality: 85));
        Storage::disk('public')->put($imagePath, (string) $original);

        // Thumbnail — 430x400. The old thumbnail.class.php stretched the full image into
        // the exact box (its aspect-ratio-preserving branch was commented out), so we
        // match that with resize() rather than cover() — resize() never crops, it just
        // stretches to fit the exact dimensions.
        $thumbPath = 'about-us/thumb/' . $uid . '.webp';
        $thumb = $manager->decodePath($file->getRealPath())
            ->resize(430, 400)
            ->encode(new WebpEncoder(quality: 85));
        Storage::disk('public')->put($thumbPath, (string) $thumb);

        return [$imagePath, $thumbPath];
    }
}