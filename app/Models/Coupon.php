<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [

        'code',

        'discount_type',
        'discount_value',

        'minimum_order_amount',
        'maximum_discount',
        'minimum_order_quantity',

        'start_date',
        'end_date',

        'usage_limit',
        'used_count',
        'customer_type',
        'visibility',

        'status',
    ];
}
