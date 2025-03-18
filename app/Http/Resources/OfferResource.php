<?php

namespace App\Http\Resources;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Offer */
class OfferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'brief' => $this->brief,
            'desc' => $this->desc,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'type' => $this->type,
            'bundle_type' => $this->bundle_type,

            'percent' =>(int) $this->percent,
            'price_before_discount' =>(int) $this->price_before_discount,
            'organization_id' => $this->organization_id,
            'offer-main_img' => $this->getFirstMediaUrl('offer_main'),
            'offer_sub_img' => $this->getMedia('offer_sub')->map(function($media) {
                return $media->getUrl();
            }),

            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->title,
                    'special_discount' => $product->pivot->specific_discount ?? null, // ✅ Access pivot correctly
                    ];
            }),
        ];
    }
}
