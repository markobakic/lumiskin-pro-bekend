<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Lightweight product payload used by GET /api/products (shop listing / grid).
 * Mirrors the static PRODUCTS shape in src/content/products.js, without `details`.
 */
class ProductSummaryResource extends JsonResource
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
        ];
    }
}
