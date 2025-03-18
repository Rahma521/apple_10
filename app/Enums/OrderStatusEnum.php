<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case pending = 1;
    case placed = 2;
    case out_for_delivery = 3;
    case delivered = 4;
    case cancelled = 5;

    public function label(): string
    {
        return match ($this) {
            self::pending => __('application.pending'),
            self::out_for_delivery => __('application.out_for_delivery'),
            self::delivered => __('application.delivered'),
            self::cancelled => __('application.cancelled'),
            self::placed => __('application.placed'),
        };
    }
}
