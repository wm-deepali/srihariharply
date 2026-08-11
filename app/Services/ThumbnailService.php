<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ThumbnailService
{
    /**
     * Generate a thumbnail from an already-stored image, matching the old
     * Thumbnail class: a hard stretch to the exact width/height, no aspect
     * ratio preservation (that logic was commented out in the original).
     */
    public static function make(string $storedPath, int $width, int $height, string $disk = 'public'): string
    {
        $directory = dirname($storedPath);
        $filename  = basename($storedPath);
        $thumbPath = $directory . '/thumb/' . $filename;

        $manager = ImageManager::usingDriver(Driver::class);
        $image   = $manager->decode(Storage::disk($disk)->path($storedPath));
        $image->resize($width, $height);

        Storage::disk($disk)->put($thumbPath, (string) $image->encode());

        return $thumbPath;
    }

    public static function delete(string $storedPath, string $disk = 'public'): void
    {
        $thumbPath = dirname($storedPath) . '/thumb/' . basename($storedPath);
        Storage::disk($disk)->delete([$storedPath, $thumbPath]);
    }
}