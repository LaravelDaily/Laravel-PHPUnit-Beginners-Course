<?php

namespace App\Services;

class CurrencyService
{

    const RATES = [
        'usd' => [
            'eur' => 0.89,
        ]
    ];

    public function convert($amount, $currency_from, $currency_to)
    {
        $rate = 0;
        if (isset(self::RATES[$currency_from])) {
            $rate = self::RATES[$currency_from][$currency_to] ?? 0;
        }

        return round($amount * $rate, 2);
    }

}