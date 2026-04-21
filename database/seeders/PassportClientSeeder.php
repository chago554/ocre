<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PassportClientSeeder extends Seeder
{
    /**art

     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('passport:keys', ['--no-interaction' => true]);
        
        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => 'Personal Access Client',
            '--no-interaction' => true, 
        ]);

        $this->command->info('Cliente personal de Passport creado con éxito.');
    }
}
