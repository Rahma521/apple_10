<?php

namespace App\Models;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use FilterableTrait;

    protected $fillable = [
        'amount',
        'transaction_id',
        'order_id',
        'last_four_digits',
        'user_id',
        'status',
    ];

    protected array $filterableColumns = [
        [
            'columns' => ['order.payment_method'],
            'type' => 'equals',
            'search_key' => 'payment_method',
        ],
        [
            'columns' => ['orderItems.product_id'],
            'type' => 'equals',
            'search_key' => 'product_id',
        ],
        [
            'columns' => 'created_at',
            'type' => 'range',
            'start_key' => 'start_date',
            'end_key' => 'end_date',
        ]

    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}
