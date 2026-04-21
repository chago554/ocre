<?php

namespace Database\Seeders;

use App\Models\SupportMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupportMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupportMessage::factory()->count(100)->create();
    }
}
