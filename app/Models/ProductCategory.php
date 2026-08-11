<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductCategory extends Model
{
    protected $fillable = ['title', 'image', 'content', 'status'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function details()
    {
        return $this->hasMany(ProductDetail::class);
    }
}