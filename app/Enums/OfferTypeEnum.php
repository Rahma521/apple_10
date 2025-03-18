<?php

namespace App\Enums;

enum OfferTypeEnum: int
{
    case single = 1;
    case bundle = 2;

    public function label(): string
    {
        return match ($this) {
            self::single => __('application.single'),
            self::bundle => __('application.bundle'),
        };
    }
}
