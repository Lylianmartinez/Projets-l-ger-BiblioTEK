<?php

namespace Database\Seeders;

use App\Models\Statut;
use Illuminate\Database\Seeder;

class StatutSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['disponible', 'emprunté', 'abîmé', 'réservé'] as $statut) {
            Statut::firstOrCreate(['statut' => $statut]);
        }
    }
}
