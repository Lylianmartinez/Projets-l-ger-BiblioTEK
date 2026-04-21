<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StatutSeeder::class,
            UserSeeder::class,
            AuteurSeeder::class,
            CategorieSeeder::class,
            LivreSeeder::class,
            ExemplaireSeeder::class,
            EmpruntSeeder::class,
        ]);
    }
}
