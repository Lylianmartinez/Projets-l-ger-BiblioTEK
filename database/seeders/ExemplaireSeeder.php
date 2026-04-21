<?php

namespace Database\Seeders;

use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;
use Illuminate\Database\Seeder;

class ExemplaireSeeder extends Seeder
{
    public function run(): void
    {
        $livres  = Livre::all();
        $statuts = Statut::all()->keyBy('statut');

        $livres->each(function ($livre) use ($statuts) {
            $nbExemplaires = rand(2, 6);
            for ($i = 0; $i < $nbExemplaires; $i++) {
                $statut = $statuts->random();
                Exemplaire::create([
                    'livre_id'        => $livre->id,
                    'statut_id'       => $statut->id,
                    'mise_en_service' => fake()->dateTimeBetween('-10 years', 'now'),
                ]);
            }
        });
    }
}
