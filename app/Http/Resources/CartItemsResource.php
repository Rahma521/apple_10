<?php

namespace App\Http\Resources;

use App\Models\CartItems;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CartItems */
class CartItemsResource extends JsonResource
{
    protected ?Organization $organization = null;

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    public function toArray(Request $request): array
    {
        $productResource = ProductDetailsResource::make($this->product);

        // If organization is set, pass it to the product resource
        if ($this->organization) {
            $productResource->setOrganization($this->organization);
        }

        return [
            'id' => $this->id,
            'price' => $this->price,
            'item_type' => $this->item_type,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'offer_id' => $this->offer_id,
            'quantity' => $this->quantity,
            'product' => $productResource,
            'offer' => OfferResource::make($this->offer),
        ];
    }
}
