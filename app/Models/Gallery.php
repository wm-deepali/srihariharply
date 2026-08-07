<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title', 'status'];

    public function details()
    {
        return $this->hasMany(GalleryDetail::class);
    }
}