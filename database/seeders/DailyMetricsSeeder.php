<?php

namespace Database\Seeders;

use App\Models\DailyMetric;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyMetricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inicio = Carbon::now()->subMonth()->startOfMonth();
        $fin = Carbon::now();
        
        while ($inicio <= $fin) {
            DailyMetric::factory()->create([
                'date' => $inicio->copy()->format('Y-m-d'),
            ]);
            $inicio->addDay();
        }
    }
}
