<?php

namespace Database\Seeders;

use App\Models\Simulation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Simulation::factory()->count(1542)->create();
    }
}
