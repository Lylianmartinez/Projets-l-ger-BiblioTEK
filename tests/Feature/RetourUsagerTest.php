<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

/**
 * Côté usager : signaler le dépôt d'un retour (la validation finale est faite
 * par un bibliothécaire — cf. ExemplaireBackOfficeTest / RetourTest).
 */
class RetourUsagerTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    public function test_la_page_retour_est_accessible_a_un_usager(): void
    {
        $this->actingAs($this->usager())->get('/retour')->assertOk();
    }

    public function test_un_usager_signale_le_retour_de_son_emprunt(): void
    {
        $usager  = $this->usager();
        $emprunt = $this->empruntActif($usager);

        $this->actingAs($usager)
            ->post("/retour/{$emprunt->id}")
            ->assertRedirect(route('profil'))
            ->assertSessionHas('info');

        // Le signalement ne clôt PAS l'emprunt : seul le bibliothécaire le valide.
        $this->assertNull($emprunt->fresh()->date_retour_effective);
    }

    public function test_un_emprunt_deja_rendu_ne_peut_pas_etre_signale(): void
    {
        $usager  = $this->usager();
        $emprunt = $this->empruntActif($usager);
        $emprunt->update(['date_retour_effective' => now()]);

        $this->actingAs($usager)
            ->post("/retour/{$emprunt->id}")
            ->assertSessionHasErrors('retour');
    }

    public function test_un_usager_ne_peut_pas_signaler_le_retour_dun_autre(): void
    {
        $proprietaire = $this->usager();
        $intrus       = $this->usager();
        $emprunt      = $this->empruntActif($proprietaire);

        $this->actingAs($intrus)
            ->post("/retour/{$emprunt->id}")
            ->assertForbidden();
    }
}
