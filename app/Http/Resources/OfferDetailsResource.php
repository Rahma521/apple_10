<?php

namespace App\Http\Resources;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Offer */
class OfferDetailsResource extends JsonResource
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
            'price_before_discount' =>(double) $this->price_before_discount,
            'price_after_discount' =>(double) $this->price_after_discount,
            'organization_id' => $this->organization_id,
            'offer_main_img' => $this->getFirstMediaUrl('offer_main'),
            'offer_sub_img' => $this->getMedia('offer_sub')->map(function($media) {
                return $media->getUrl();
            }),
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->title,
                    'price' =>(int) $product->price,
                    'price_after_general_discount' => $product->price - ($product->price *$this->percent / 100),
                    'special_discount' => $product->pivot->specific_discount ?? null,
                    'price_after_special_discount' => $product->price - ($product->price * $product->pivot->specific_discount / 100),
                    'sub_category_id' => $product->sub_category_id,
                    'sub_category' => $product->subCategory->name,
                    'main_category_id' => $product->subCategory->parent->id,
                    'main_category' => $product->subCategory->parent->name,
                    'product_banner' => $product->getFirstMediaUrl('product_banner'),

                ];
            }),
            'translations' => $this->translations ?? [],
        ];
    }
}
