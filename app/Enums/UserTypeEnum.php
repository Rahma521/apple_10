<?php

namespace App\Enums;

enum UserTypeEnum: int
{
    case instructor = 1;
    case student = 2;
    case other = 3;

    public function label(): string
    {
        return match ($this) {
            self::instructor => __('application.educator'),
            self::student => __('application.student'),
            self::other => __('application.other'),
        };
    }
}
