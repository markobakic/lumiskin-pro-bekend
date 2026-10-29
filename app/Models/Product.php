<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'num',
        'name',
        'subtitle',
        'description',
        'description_long',
        'features',
        'price',
        'accent',
        'accent_strong',
        'use_img',
        'image',
        'details',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'details' => 'array',
        'use_img' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'integer',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
