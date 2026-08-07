<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurService extends Model
{
    use HasFactory;

    protected $table = 'our_services';

    protected $fillable = [
        'title',
        'content',
        'status',
    ];
}