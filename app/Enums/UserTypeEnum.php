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
            self::instructor => 'application.educator',
            self::student =>'application.student',
            self::other => 'application.other',
        };
    }
}
