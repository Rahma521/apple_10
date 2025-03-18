<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class SubCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'main_category_name' => $this->parent?->name,
            'name' => $this->name,
            'has_products' => $this->hasProducts(),
            'translations' => $this->translations ?? [],
           // 'products' => ProductResource::collection($this->products),
        ];
    }
}
