<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

/**
 * Gestion des usagers par le bibliothécaire (préfixe /bo, controller Admin\UsagerController).
 */
class UsagerManagementTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    public function test_la_liste_des_usagers_est_accessible(): void
    {
        $this->usager(['name' => 'Camille Roux']);

        $this->actingAs($this->bibliothecaire())
            ->get('/bo/usagers')
            ->assertOk()
            ->assertSee('Camille Roux');
    }

    public function test_la_recherche_filtre_les_usagers(): void
    {
        $this->usager(['name' => 'Aline Cherchee', 'email' => 'aline@example.com']);
        $this->usager(['name' => 'Bob Absent', 'email' => 'bob@example.com']);

        $this->actingAs($this->bibliothecaire())
            ->get('/bo/usagers?q=Aline')
            ->assertOk()
            ->assertSee('Aline Cherchee')
            ->assertDontSee('Bob Absent');
    }

    public function test_le_detail_dun_usager_est_accessible(): void
    {
        $usager = $this->usager(['name' => 'Detail Usager']);

        $this->actingAs($this->bibliothecaire())
            ->get("/bo/usager/{$usager->id}")
            ->assertOk()
            ->assertSee('Detail Usager');
    }

    public function test_le_formulaire_de_modification_est_accessible(): void
    {
        $usager = $this->usager();

        $this->actingAs($this->bibliothecaire())
            ->get("/bo/usager/{$usager->id}/modifier")
            ->assertOk();
    }

    public function test_un_bibliothecaire_peut_modifier_un_usager(): void
    {
        $usager = $this->usager(['name' => 'Ancien Nom', 'email' => 'ancien@example.com']);

        $this->actingAs($this->bibliothecaire())
            ->post("/bo/usager/{$usager->id}/modifier", [
                'name'  => 'Nouveau Nom',
                'email' => 'nouveau@example.com',
                'role'  => 'bibliothecaire',
            ])
            ->assertRedirect(route('bo.usagers.show', $usager));

        $usager->refresh();
        $this->assertSame('Nouveau Nom', $usager->name);
        $this->assertSame('nouveau@example.com', $usager->email);
        $this->assertSame('bibliothecaire', $usager->role);
    }

    public function test_la_modification_valide_les_donnees(): void
    {
        $usager = $this->usager();

        $this->actingAs($this->bibliothecaire())
            ->post("/bo/usager/{$usager->id}/modifier", [
                'name'  => '',
                'email' => 'pas-un-email',
                'role'  => 'roi',
            ])
            ->assertSessionHasErrors(['name', 'email', 'role']);
    }

    public function test_un_usager_garde_son_propre_email_sans_conflit_dunicite(): void
    {
        $usager = $this->usager(['name' => 'Stable', 'email' => 'stable@example.com']);

        $this->actingAs($this->bibliothecaire())
            ->post("/bo/usager/{$usager->id}/modifier", [
                'name'  => 'Stable Modifie',
                'email' => 'stable@example.com',
                'role'  => 'usager',
            ])
            ->assertSessionHasNoErrors();
    }

    public function test_un_email_deja_pris_par_un_autre_est_rejete(): void
    {
        $this->usager(['email' => 'occupe@example.com']);
        $cible = $this->usager(['email' => 'cible@example.com']);

        $this->actingAs($this->bibliothecaire())
            ->post("/bo/usager/{$cible->id}/modifier", [
                'name'  => 'Cible',
                'email' => 'occupe@example.com',
                'role'  => 'usager',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_la_suspension_bascule_letat_actif(): void
    {
        $usager = $this->usager(['is_active' => true]);
        $biblio = $this->bibliothecaire();

        $this->actingAs($biblio)->post("/bo/usager/{$usager->id}/suspendre")->assertRedirect();
        $this->assertFalse((bool) $usager->fresh()->is_active);

        $this->actingAs($biblio)->post("/bo/usager/{$usager->id}/suspendre")->assertRedirect();
        $this->assertTrue((bool) $usager->fresh()->is_active);
    }

    public function test_un_usager_sans_emprunt_en_cours_peut_etre_supprime(): void
    {
        $usager = $this->usager();

        $this->actingAs($this->bibliothecaire())
            ->delete("/bo/usager/{$usager->id}/supprimer")
            ->assertRedirect(route('bo.usagers.index'));

        $this->assertDatabaseMissing('users', ['id' => $usager->id]);
    }

    public function test_un_usager_avec_emprunt_en_cours_ne_peut_pas_etre_supprime(): void
    {
        $usager = $this->usager();
        $this->empruntActif($usager);

        $this->actingAs($this->bibliothecaire())
            ->delete("/bo/usager/{$usager->id}/supprimer")
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $usager->id]);
    }

    public function test_un_usager_ne_peut_pas_gerer_les_usagers(): void
    {
        $cible = $this->usager();

        $this->actingAs($this->usager())->get('/bo/usagers')->assertForbidden();
        $this->actingAs($this->usager())
            ->delete("/bo/usager/{$cible->id}/supprimer")
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $cible->id]);
    }
}
