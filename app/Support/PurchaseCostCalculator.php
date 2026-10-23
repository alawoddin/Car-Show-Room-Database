<?php

namespace App\Support;

class PurchaseCostCalculator
{
    /**
     * Cost formula:
     * - USD: buying_fee + towing_fee + shipping
     * - AED: Total AED + Clearing + Commission + Custom Duty = Grand Total
     *
     * @param  array{
     *     buying_fee?: float|int|string|null,
     *     towing_fee?: float|int|string|null,
     *     shipping?: float|int|string|null,
     *     clearing?: float|int|string|null,
     *     commission?: float|int|string|null,
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
        $commission = self::num($input['commission'] ?? 0);
        $selling = self::num($input['selling_price'] ?? 0);

        $rate = (float) config('purchase.usd_to_aed', 3.675);
        $dutyBase = (float) config('purchase.duty_base', 1472);
        $dutyRate = (float) config('purchase.duty_rate', 0.055);

        $totalUsd = round($buying + $towing + $shipping, 2);
        $totalAed = round($totalUsd * $rate, 2);

        // Empty purchase → no duty
        $customDuty = $totalAed > 0
            ? round(($totalAed + $dutyBase) * $dutyRate, 2)
            : 0.0;

        // Grand Total = Total AED + Clearing + Commission + Custom Duty
        $grandTotal = round($totalAed + $clearing + $commission + $customDuty, 2);
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
