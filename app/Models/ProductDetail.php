<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductDetail extends Model
{
    protected $fillable = [
        'product_category_id',
        'brand_id',
        'title',
        'url',
        'image',
        'thumb',
        'content',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        return $this->thumb ? Storage::disk('public')->url($this->thumb) : null;
    }
}