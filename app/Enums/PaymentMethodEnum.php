<?php

namespace App\Enums;

enum PaymentMethodEnum: int
{
   // case cash = 1;
    case tabby = 2;
    case visa = 3;
    case mastercard = 4;
    case mada = 5;

    public function label(): string
    {
        return match ($this) {
          //  self::cash => __('application.cash_on_delivery'),
            self::tabby => __('application.tabby'),
            self::visa => __('application.visa'),
            self::mastercard => __('application.mastercard'),
            self::mada => __('application.mada'),
        };
    }
}
