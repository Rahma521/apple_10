<?php

namespace App\Http\Resources;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Brand */
class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id'   => $this->id,
            'name' => $this->name,
            'has_categories' => $this->hasCategories(),
            'files' => $this->getFirstMediaUrl('brands'),

            'translations' => $this->translations ?? [],
        ];
    }
}
