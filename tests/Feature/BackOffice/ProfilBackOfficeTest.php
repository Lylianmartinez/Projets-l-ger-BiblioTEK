<?php

namespace Tests\Feature\BackOffice;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

class ProfilBackOfficeTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    private const PROFILS_URL = '/bo/profils';

    public function test_la_liste_des_profils_est_accessible_au_bibliothecaire(): void
    {
        $this->usager(['name' => 'Usager Visible']);

        $this->actingAs($this->bibliothecaire())
            ->get(self::PROFILS_URL)
            ->assertOk()
            ->assertSee('Usager Visible');
    }

    public function test_le_detail_dun_profil_usager_est_accessible(): void
    {
        $usager = $this->usager(['name' => 'Jean Dupont']);

        $this->actingAs($this->bibliothecaire())
            ->get("/bo/profil/{$usager->id}")
            ->assertOk()
            ->assertSee('Jean Dupont');
    }

    public function test_un_usager_ne_peut_pas_acceder_aux_profils_du_back_office(): void
    {
        $this->actingAs($this->usager())->get(self::PROFILS_URL)->assertForbidden();
    }

    public function test_un_visiteur_est_redirige_vers_la_connexion(): void
    {
        $this->get(self::PROFILS_URL)->assertRedirect(route('connexion'));
    }
}
