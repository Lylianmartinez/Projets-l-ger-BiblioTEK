<?php

namespace Database\Seeders;

use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmpruntSeeder extends Seeder
{
    public function run(): void
    {
        $usagers          = User::where('role', 'usager')->get();
        $statutEmprunte   = Statut::where('statut', 'emprunté')->first();
        $statutDisponible = Statut::where('statut', 'disponible')->first();
        $exemplaires      = Exemplaire::where('statut_id', $statutDisponible->id)->get();

        for ($i = 0; $i < 60; $i++) {
            $dateEmprunt = fake()->dateTimeBetween('-2 years', '-35 days');
            $dateRetourPrevue = (clone $dateEmprunt)->modify('+30 days');
            $estRendu = fake()->boolean(80);

            $emprunt = Emprunt::create([
                'user_id'               => $usagers->random()->id,
                'date_emprunt'          => $dateEmprunt,
                'date_retour_prevue'    => $dateRetourPrevue,
                'date_retour_effective' => $estRendu
                    ? fake()->dateTimeBetween($dateEmprunt, 'now')
                    : null,
            ]);

            // Attacher 1 à 5 exemplaires
            $nbExemplaires = rand(1, 5);
            $choisis = $exemplaires->random(min($nbExemplaires, $exemplaires->count()));
            $emprunt->exemplaires()->attach($choisis->pluck('id')->toArray());

            // Mettre à jour le statut si non rendu
            if (!$estRendu) {
                $choisis->each(fn ($e) => $e->update(['statut_id' => $statutEmprunte->id]));
            }
        }
    }
}
