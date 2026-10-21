<?php

namespace App\Support;

class PurchaseCostCalculator
{
    /**
     * Match Excel cash book:
     * - USD: buying_fee + towing_fee + shipping
     * - AED: clearing, surcharge (extra_charges), custom duty, grand total, sale
     *
     * Example (Excel):
     *   5255 + 195 + 2300 = 7750 USD
     *   7750 × 3.675 = 28481.25 AED
     *   duty = (28481.25 + 1472) × 0.055 = 1647.43
     *   grand = 28481.25 + 1400 + 0 + 1647.43 = 31528.68
     *
     * @param  array{
     *     buying_fee?: float|int|string|null,
     *     towing_fee?: float|int|string|null,
     *     shipping?: float|int|string|null,
     *     clearing?: float|int|string|null,
     *     extra_charges?: float|int|string|null,
     *     selling_price?: float|int|string|null
     * }  $input
     * @return array{
     *     total_usd: float,
     *     total_aed: float,
     *     custom_duty: float,
     *     grand_total: float,
     *     profit: float,
     *     exchange_rate: float
     * }
     */
    public static function calculate(array $input): array
    {
        $buying = self::num($input['buying_fee'] ?? 0);
        $towing = self::num($input['towing_fee'] ?? 0);
        $shipping = self::num($input['shipping'] ?? 0);
        $clearing = self::num($input['clearing'] ?? 0);
        $extra = self::num($input['extra_charges'] ?? 0);
        $selling = self::num($input['selling_price'] ?? 0);

        $rate = (float) config('purchase.usd_to_aed', 3.675);
        $dutyBase = (float) config('purchase.duty_base', 1472);
        $dutyRate = (float) config('purchase.duty_rate', 0.055);

        $totalUsd = round($buying + $towing + $shipping, 2);
        $totalAed = round($totalUsd * $rate, 2);

        // Empty purchase → no duty (do not show 80.96)
        $customDuty = $totalAed > 0
            ? round(($totalAed + $dutyBase) * $dutyRate, 2)
            : 0.0;

        // Same as Excel: Total AED + Clearing + Surcharge + Custom Duty
        $grandTotal = round($totalAed + $clearing + $extra + $customDuty, 2);
        $profit = round($selling - $grandTotal, 2);

        return [
            'total_usd' => $totalUsd,
            'total_aed' => $totalAed,
            'custom_duty' => $customDuty,
            'grand_total' => $grandTotal,
            'profit' => $profit,
            'exchange_rate' => $rate,
        ];
    }

    private static function num(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return (float) $value;
    }
}
