<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Full product payload used by GET /api/products/{slug}.
 * `id` is exposed as the slug so the Next.js site keeps using /shop/{id}.
 * The frontend destructures `{ details, ...product }` (see src/lib/api.js).
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'category' => $this->category,
            'num' => $this->num,
            'name' => $this->name,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'descriptionLong' => $this->description_long,
            'features' => $this->features ?? [],
            'price' => $this->price,
            'accent' => $this->accent,
            'accentStrong' => $this->accent_strong,
            'useImg' => (bool) $this->use_img,
            'image' => $this->image,
            'details' => $this->details,
        ];
    }
}
