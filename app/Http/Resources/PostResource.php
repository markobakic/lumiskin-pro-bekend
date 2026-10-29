<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Blog post payload. Mirrors the shape in src/content/posts.js.
 * `date` is ISO (YYYY-MM-DD) — the frontend splits on '-' in formatDate().
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'date' => $this->date?->format('Y-m-d'),
            'readingTime' => $this->reading_time,
            'sections' => $this->sections ?? [],
        ];
    }
}
