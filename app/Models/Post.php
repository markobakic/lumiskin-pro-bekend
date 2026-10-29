<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'date',
        'reading_time',
        'sections',
        'is_published',
    ];

    protected $casts = [
        'sections' => 'array',
        'date' => 'date',
        'reading_time' => 'integer',
        'is_published' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
