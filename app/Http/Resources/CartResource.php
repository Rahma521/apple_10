<?php

namespace App\Http\Resources;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Cart */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Get the user's organization
        $organization = $this->user?->organization;

        // Create cart items collection and pass organization to each item
        $cartItems = $this->cartItems->map(function($item) use ($organization) {
            // For products, we need to load the specific organization relationship
            if ($item->item_type === 'product' && $item->product && $organization) {
                $item->product->load([
                    'assignedOrganizations' => function ($query) use ($organization) {
                        $query->where('organizations.id', $organization->id)
                            ->withPivot('discount');
                    }
                ]);
            }
            return (new CartItemsResource($item))->setOrganization($organization);
        });

        return [
            'id' => $this->id,
            'total_before_discount' => $this->total_before_discount,
            'total_discount' => round($this->total_before_discount - $this->total_after_discount,2),
            'price_after_discount' => round($this->total_after_discount,2),
            'vat' => round($this->total_after_discount * 0.15,2),
            'delivery_cost' => $this->user?->organization?->delivery_price,
            'total' => $this->user?->organization?->delivery_price + round($this->total_after_discount + ($this->total_after_discount * 0.15), 2),
            'user' => $this->user->name,
            'cart_items_quantity' => $this->cartQuantity(),
            'cart_items' => $cartItems,
        ];
    }
}
