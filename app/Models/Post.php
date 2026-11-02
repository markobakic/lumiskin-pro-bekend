<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'image',
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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
