<?php

namespace App\Http\Resources;

use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PaymentTransaction */
class PaymentTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'transaction_id' => $this->transaction_id,
            'last_four_digits' => $this->last_four_digits,
            'status' => $this->status,
            'order_id' => $this->order_id,
            'payment_method' => $this->order->payment_method?->label(),
            'payment_method_id' => $this->order->payment_method,
            'payment_status' => $this->order->payment_status,
            'order_status' => $this->order->order_status?->label(),
            'order_status_id' => $this->order->order_status,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,

            // 'order' => new OrderResource($this->whenLoaded('order')),
        ];
    }
}
