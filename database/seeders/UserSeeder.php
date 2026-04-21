<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Pest\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => "Santiago",
            'last_name' => "Laureano",
            'email' => 'admin@ocre.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => "admin",
            'avatar' => Str::random(100),
            'remember_token' => Str::random(10),
        ]);

        User::factory()->count(100)->create();
    }
}
