<?php

namespace App\Http\Resources;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_type' => $this->item_type,

            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'offer_id' => $this->offer_id,
            'quantity' => $this->quantity,
            'product' => ProductResource ::make($this->product),
            'offer' => OfferResource::make($this->offer),

        ];
    }
}
