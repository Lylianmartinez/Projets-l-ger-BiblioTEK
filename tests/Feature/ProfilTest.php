<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

class ProfilTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    private const PROFIL_URL = '/profil';

    public function test_le_profil_est_accessible_a_un_usager_connecte(): void
    {
        $this->actingAs($this->usager())->get(self::PROFIL_URL)->assertOk();
    }

    public function test_le_profil_distingue_emprunt_actif_et_historique(): void
    {
        $usager  = $this->usager();
        $emprunt = $this->empruntActif($usager);

        $response = $this->actingAs($usager)->get(self::PROFIL_URL);
        $response->assertOk();
        $response->assertViewHas('empruntActif', fn ($actif) => $actif !== null && $actif->id === $emprunt->id);
        $response->assertViewHas('historique', fn ($h) => $h->isEmpty());

        // Une fois rendu, il bascule dans l'historique.
        $emprunt->update(['date_retour_effective' => now()]);

        $response = $this->actingAs($usager)->get(self::PROFIL_URL);
        $response->assertViewHas('empruntActif', null);
        $response->assertViewHas('historique', fn ($h) => $h->count() === 1);
    }

    public function test_un_bibliothecaire_ne_peut_pas_acceder_au_profil_usager(): void
    {
        $this->actingAs($this->bibliothecaire())->get(self::PROFIL_URL)->assertForbidden();
    }
}
