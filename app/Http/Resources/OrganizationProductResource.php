<?php

namespace App\Http\Resources;

use App\Models\OrganizationProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrganizationProduct */
class OrganizationProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'product_id' => $this->product_id,
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
        ];
    }
}
