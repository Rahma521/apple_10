<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'payment_method' => $this->payment_method?->label(),
            'payment_method_id' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status?->label(),
            'order_status_id' => $this->order_status,
            'delivery_cost' => $this->user?->organization?->delivery_price,
            'total' => $this->total,
            'user' => $this->user?->name,
            'email' => $this->user?->email,

            'organization' => $this->user?->organization?->name,
            'cart_id' => $this->cart_id,
            'created_at' => $this->created_at,

            'address' => AddressResource::make($this->address),
            'order_items' => OrderItemResource::collection($this->orderItems->load(['product', 'offer']))
        ];
    }
}
