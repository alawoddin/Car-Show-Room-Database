<?php

return [

    /*
    |--------------------------------------------------------------------------
    | USD → AED exchange rate
    |--------------------------------------------------------------------------
    |
    | UAE dirham is pegged to the US dollar (official: 3.6725).
    | Override with USD_TO_AED in .env if needed.
    |
    */
    // Excel / cash book rate (5255+195+2300)×3.675 = 28481.25
    'usd_to_aed' => (float) env('USD_TO_AED', 3.675),

    /*
    |--------------------------------------------------------------------------
    | Custom duty formula
    |--------------------------------------------------------------------------
    |
    | duty = (total_aed + duty_base) × duty_rate
    |
    */
    'duty_base' => (float) env('PURCHASE_DUTY_BASE', 1472),
    'duty_rate' => (float) env('PURCHASE_DUTY_RATE', 0.055),

];
