<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'category_id',
        'subcategory_id',

        'name',
        'slug',

        'sku',
        'product_code',
        'hsn_code',

        'sub_title',
        'weight',
        'description',
        'product_notes',
        'how_to_use',
        'the_story',
        'detail_page_color',

        'mrp',
        'discount_type',
        'discount',
        'price',

        'stock',
        'min_qty',

        'delivery_time',

        'quality',
        'pan_india',

        'meta_title',
        'meta_description',

        'status',

    ];

    protected $casts = [

        'status' => 'boolean',
        'quality' => 'boolean',
        'pan_india' => 'boolean',
        'product_notes' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }


    public function defaultImage()
    {
        return $this->hasOne(ProductImage::class)->where('image_type', 'default');
    }

    public function hoverImage()
    {
        return $this->hasOne(ProductImage::class)->where('image_type', 'hover');
    }

    public function bannerImages()
    {
        return $this->hasMany(ProductImage::class)->where('image_type', 'banner');
    }

    public function storyImage()
    {
        return $this->hasMany(ProductImage::class)->where('image_type', 'story');
    }

    // Update the existing accessor
    public function getDisplayImageAttribute()
    {
        $img = $this->defaultImage ?? $this->images()->first();
        return $img ? asset('storage/' . $img->image) : null;
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function collections()
    {
        return $this->belongsToMany(
            Collection::class,
            'collection_product',
            'product_id',
            'collection_id'
        );
    }

    public function cartItems()
    {
        return $this->hasMany(
            CartItem::class
        );
    }

    public function scopeVisible($query)
    {
        $query->where('status', 1);

        if (StockSetting::current()->auto_disable_out_of_stock) {
            $query->where('stock', '>', 0);
        }

        return $query;
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }


    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }


    // OCCASIONS
    public function occasions()
    {
        return $this->belongsToMany(
            GiftingOccasion::class,
            'occasion_product',
            'product_id',
            'occasion_id'
        );
    }



}