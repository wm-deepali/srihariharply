<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';

    protected $fillable = [
        'title',
        'image',
        'thumb',
        'content',
        'status',
    ];

    /**
     * Full-size image (original, WebP compressed).
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    /**
     * 430x400 resized thumbnail — used on the front-end "Welcome" section.
     */
    public function getThumbUrlAttribute(): ?string
    {
        return $this->thumb ? Storage::disk('public')->url($this->thumb) : null;
    }
}