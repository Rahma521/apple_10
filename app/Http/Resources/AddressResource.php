<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Address */
class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'zip_code' => $this->zip_code,
            'district' => $this->district,
            'short_address' => $this->short_address,
            'full_address' => $this->full_address,
            'type' => $this->type,
            'city' => $this->city?->name,
            'city_id' => $this->city_id,
            'region' => $this->city?->region?->id,
        ];
    }
}
