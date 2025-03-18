<?php


namespace App\Enums;

enum ContactFormTypes: int
{
    case it_contact = 3;
    case consultation = 2;
    case general_contact = 1;



    public function label(): string
    {
        return match ($this) {
            self::general_contact => __('application.general_contact'),
            self::consultation => __('application.consultation'),
            self::it_contact => __('application.it_contact'),

        };
    }
}
