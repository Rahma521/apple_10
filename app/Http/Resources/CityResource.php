<?php

namespace App\Http\Resources;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Brand */
class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' =>(int) $this->id,
            'name' =>$this->name,
            'has_users' => $this->hasUsers(),
            'has_organization' => $this->hasOrganizations(),
            'has_addresses' => $this->hasAddresses(),
            'region' => RegionResource::make($this->whenLoaded('region')),
            'translations' => $this->translations ?? [],

        ];
    }
}
