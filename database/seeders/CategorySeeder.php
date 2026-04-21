<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $categories = [
            [
                'name' => 'Comida',
                'icon' => 'utensils',        
                'type' => 'gasto'
            ],
            [
                'name' => 'Transporte',
                'icon' => 'car',              
                'type' => 'gasto'
            ],
            [
                'name' => 'Entretenimiento',
                'icon' => 'clapperboard',     
                'type' => 'gasto'
            ],
            [
                'name' => 'Salario',
                'icon' => 'briefcase',        
                'type' => 'ingreso'
            ],
            [
                'name' => 'Servicios',
                'icon' => 'zap',              
                'type' => 'gasto'
            ],
            [
                'name' => 'Ahorro',
                'icon' => 'piggy-bank',       
                'type' => 'meta'              
            ]
        ];
        
        foreach ($categories as $item) {
            Category::create([
                'name' => $item['name'],   
                'icon' => $item['icon'],
                'type' => $item['type']
            ]);
        }

    }
}