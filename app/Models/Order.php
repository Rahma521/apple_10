<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use FilterableTrait;

    protected $fillable = [
        'user_id',
        'cart_id',
        'address_id',
        'order_number',
        'payment_method',
        'payment_status',
        'order_status',
        'total',
    ];

    /**
     * @var array
     */
    protected array $filterableColumns = [
        [
            'columns' => ['user.organization_id'],
            'type' => 'equals',
            'search_key' => 'organization_id',
        ],
        [
            'columns' => ['user.email'],
            'type' => 'like',
            'search_key' => 'search',
        ],
        [
            'columns' => ['payment_method'],
            'type' => 'equals',
            'search_key' => 'payment_method',
        ],
        [
            'columns' => ['order_status'],
            'type' => 'equals',
            'search_key' => 'order_status',
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

    protected $casts =[
        'payment_method' => PaymentMethodEnum::class,
        'order_status' => OrderStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
