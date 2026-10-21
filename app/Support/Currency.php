<?php

namespace App\Support;

class Currency
{
    public static function rate(): float
    {
        return (float) config('purchase.usd_to_aed', 3.675);
    }

    public static function usdToAed(float|int|string|null $usd): float
    {
        return round(self::num($usd) * self::rate(), 2);
    }

    public static function formatAed(float|int|string|null $amount): string
    {
        return 'AED '.number_format(self::num($amount), 2);
    }

    private static function num(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return (float) $value;
    }
}
