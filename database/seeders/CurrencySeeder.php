<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Seed the initial currencies, keyed by their unique code.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'Dólar estadounidense',
                'code' => 'USD',
                'symbol' => '$',
                'exchange_rate' => 1.000000,
            ],
            [
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'exchange_rate' => 0.920000,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency,
            );
        }
    }
}
