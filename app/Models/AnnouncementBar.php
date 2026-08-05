<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementBar extends Model
{
    protected $fillable = [
        'message',
        'link_text',
        'link_url',
        'bg_color',
        'text_color',
        'is_active',
        'is_dismissible',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_dismissible' => 'boolean',
    ];
}