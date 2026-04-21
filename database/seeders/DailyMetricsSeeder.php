<?php

namespace Database\Seeders;

use App\Models\DailyMetric;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyMetricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DailyMetric::factory()->count(1452)->create();
    }
}
