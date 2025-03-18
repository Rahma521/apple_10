<?php

namespace App\Enums;

enum OfferBundleTypeEnum: int
{
    case general = 1;
    case specific = 2;

    public function label(): string
    {
        return match ($this) {
            self::general => __('application.general'),
            self::specific => __('application.specific'),
        };
    }
}
