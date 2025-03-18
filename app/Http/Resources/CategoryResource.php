<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'brand_id' => $this->brand?->id,
            'brand_name' => $this->brand?->name,
            'main_category_id' => $this->parent?->id,
            'main_category_name' => $this->parent?->name,
            'has_sub_categories' => $this->hasChildren(),
            'has_products' => $this->hasProducts(),
            'name' => $this->name,
            'files' => $this->getFirstMediaUrl('categories'),
            'subCategories' => SubCategoryResource::collection($this->whenLoaded('children')),
            'translations' => $this->translations ?? [],

        ];
    }
}
