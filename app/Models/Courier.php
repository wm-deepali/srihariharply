<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'name',
        'website_url',
        'is_active',
        'is_default'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}