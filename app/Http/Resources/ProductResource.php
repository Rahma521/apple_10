<?php

namespace App\Http\Resources;

use App\Enums\OfferTypeEnum;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Brand */
class ProductResource extends JsonResource
{
    protected ?Organization $organization = null;

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'part_number' => $this->part_number,
            'upc_ean' => $this->upc_ean,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'description' => $this->description,
            'features' => $this->features,
            'legal' => $this->legal,
            'specifications' => $this->specifications,
            'technical_specifications' => $this->technical_specifications,
            'price' => (int)$this->price,
            'product_banner' => $this->getFirstMediaUrl('product_banner'),
            'visible' => $this->visible,
            'available' => $this->available,
            'sub_category_id' => $this->sub_category_id,
            'sub_category' => $this->subCategory?->name,
            'main_category_id' => $this->subCategory?->parent?->id,
            'main_category' => $this->subCategory?->parent?->name,
            'color_images' => $this->getMedia('color_img')->map(function($media) {
                return $media->getUrl();
            }),
            'color' => $this->colors?->code,
            'is_in_single_offer' => $this->when($this->organization, function () {
                return $this->offers->isNotEmpty(); // Now we can check the loaded relationship
            }),
            'has_offers' => $this->hasOffers(),
            'has_orders' => $this->hasOrders(),
            'assigned_organizations' => $this->assignedOrganizations->map(function($organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'discount' =>(int) $organization->pivot->discount ?? 0,
                ];
            }),
        ];
    }
}
