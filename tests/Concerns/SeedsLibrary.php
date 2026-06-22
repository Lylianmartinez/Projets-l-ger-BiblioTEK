<?php

namespace Tests\Concerns;

use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;
use App\Models\User;

/**
 * Petits helpers pour monter rapidement un jeu de données cohérent
 * (statuts, livres, exemplaires, emprunts) dans les tests feature/unit.
 */
trait SeedsLibrary
{
    /** Crée les statuts métier et renvoie un map ['disponible' => Statut, 'emprunté' => Statut]. */
    protected function seedStatuts(): array
    {
        return [
            'disponible' => Statut::firstOrCreate(['statut' => 'disponible']),
            'emprunté'   => Statut::firstOrCreate(['statut' => 'emprunté']),
        ];
    }

    protected function usager(array $attrs = []): User
    {
        return User::factory()->create($attrs + ['role' => 'usager']);
    }

    protected function bibliothecaire(array $attrs = []): User
    {
        return User::factory()->bibliothecaire()->create($attrs);
    }

    /** Un exemplaire rattaché à un livre, avec le statut fourni (disponible par défaut). */
    protected function exemplaire(?Statut $statut = null, array $attrs = []): Exemplaire
    {
        $statut ??= Statut::firstOrCreate(['statut' => 'disponible']);
        $livre = Livre::factory()->create();

        return Exemplaire::factory()->create($attrs + [
            'livre_id'        => $livre->id,
            'statut_id'       => $statut->id,
            'mise_en_service' => now()->subYear(),
        ]);
    }

    /** Un emprunt actif (non rendu) pour l'usager, avec un exemplaire passé "emprunté". */
    protected function empruntActif(User $usager): Emprunt
    {
        $statuts = $this->seedStatuts();
        $exemplaire = $this->exemplaire($statuts['emprunté']);

        $emprunt = Emprunt::create([
            'user_id'            => $usager->id,
            'date_emprunt'       => now(),
            'date_retour_prevue' => now()->addDays(30),
        ]);
        $emprunt->exemplaires()->attach($exemplaire->id);

        return $emprunt;
    }
}
