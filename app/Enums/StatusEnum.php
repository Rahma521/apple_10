<?php

namespace App\Enums;

enum StatusEnum: int
{
    case active = 1;
    case inactive = 2;

    public function label(): string
    {
        return match ($this) {
            self::active => __('application.active'),
            self::inactive => __('application.inactive'),
        };
    }
}
