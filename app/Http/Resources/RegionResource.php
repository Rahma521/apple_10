<?php

namespace App\Http\Resources;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Brand */
class RegionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'name' => $this->name,
            'cities' => CityResource::collection($this->whenLoaded('cities')),
            'has_cities' => $this->hasCities(),
            'translations' => $this->translations ?? [],

        ];
    }
}
