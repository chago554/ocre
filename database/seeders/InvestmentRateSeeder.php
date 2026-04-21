<?php

namespace Database\Seeders;

use App\Models\InvestmentRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $investments = [
            [
                'instrument_name' => 'CETES',
                'annual_rate' => 7.0
            ],
            [
                'instrument_name' => 'S&P 500',
                'annual_rate' => 12.0
            ],
            [
                'instrument_name' => 'Bonos gubernamentales (UDIBONOS)',
                'annual_rate' => 5.5
            ],
            [
                'instrument_name' => 'Fibras (Bienes raíces)',
                'annual_rate' => 8.2
            ],
            [
                'instrument_name' => 'Acciones de dividendos (Ej. Pfizer)',
                'annual_rate' => 4.5
            ],
            [
                'instrument_name' => 'Cuenta de ahorro de alto rendimiento',
                'annual_rate' => 2.3
            ],
            [
                'instrument_name' => 'Deuda corporativa (Grado de inversión)',
                'annual_rate' => 6.8
            ],
            [
                'instrument_name' => 'Plataformas de crowdfunding inmobiliario',
                'annual_rate' => 10.5
            ],
            [
                'instrument_name' => 'ETF de mercados emergentes',
                'annual_rate' => 9.3
            ]
        ];

        $now = now();
        foreach ($investments as $investment) {
            $investment['created_at'] = $now;
            $investment['updated_at'] = $now;
            InvestmentRate::insert($investment);
        }
    }
}
