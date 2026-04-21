<?php

namespace Tests\Feature;

use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetourTest extends TestCase
{
    use RefreshDatabase;

    private function creerEmpruntActif(User $usager): array
    {
        $dispo    = Statut::create(['statut' => 'disponible']);
        $emprunte = Statut::create(['statut' => 'emprunté']);

        $livre      = Livre::factory()->create();
        $exemplaire = Exemplaire::factory()->create([
            'livre_id'        => $livre->id,
            'statut_id'       => $emprunte->id,
            'mise_en_service' => now()->subYear(),
        ]);

        $emprunt = Emprunt::create([
            'user_id'            => $usager->id,
            'date_emprunt'       => now(),
            'date_retour_prevue' => now()->addDays(30),
        ]);
        $emprunt->exemplaires()->attach($exemplaire->id);

        return [$emprunt, $exemplaire, $dispo];
    }

    public function test_la_page_retour_est_accessible_pour_un_usager(): void
    {
        $usager = User::factory()->create(['role' => 'usager']);
        $this->actingAs($usager)->get('/retour')->assertStatus(200);
    }

    public function test_le_bibliothecaire_peut_valider_un_retour(): void
    {
        $usager  = User::factory()->create(['role' => 'usager']);
        $biblio  = User::factory()->bibliothecaire()->create();

        [$emprunt, $exemplaire, $dispo] = $this->creerEmpruntActif($usager);

        $this->actingAs($biblio)
            ->post("/bo/retour/{$emprunt->id}/valider")
            ->assertRedirect();

        $this->assertNotNull($emprunt->fresh()->date_retour_effective);
        $this->assertEquals($dispo->id, $exemplaire->fresh()->statut_id);
    }

    public function test_un_retour_deja_valide_ne_peut_pas_etre_revalide(): void
    {
        $usager = User::factory()->create(['role' => 'usager']);
        $biblio = User::factory()->bibliothecaire()->create();

        [$emprunt] = $this->creerEmpruntActif($usager);
        $emprunt->update(['date_retour_effective' => now()]);

        $this->actingAs($biblio)
            ->post("/bo/retour/{$emprunt->id}/valider")
            ->assertSessionHasErrors('retour');
    }
}
