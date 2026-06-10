<?php

namespace Tests\Feature;

use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExemplaireBackOfficeTest extends TestCase
{
    use RefreshDatabase;

    private User $biblio;
    private Statut $statut;
    private Livre $livre;

    protected function setUp(): void
    {
        parent::setUp();

        $this->biblio = User::factory()->bibliothecaire()->create();
        $this->statut = Statut::create(['statut' => 'disponible']);
        $this->livre  = Livre::factory()->create();
    }

    public function test_liste_exemplaires_accessible(): void
    {
        $this->actingAs($this->biblio)->get('/bo/exemplaires')->assertStatus(200);
    }

    public function test_formulaire_creation_accessible(): void
    {
        $this->actingAs($this->biblio)->get('/bo/exemplaire/ajout')->assertStatus(200);
    }

    public function test_un_bibliothecaire_peut_creer_un_exemplaire(): void
    {
        $this->actingAs($this->biblio)->post('/bo/exemplaire/ajout', [
            'livre_id'        => $this->livre->id,
            'statut_id'       => $this->statut->id,
            'mise_en_service' => '2024-01-15',
        ])->assertRedirect(route('bo.exemplaires'));

        $this->assertDatabaseHas('exemplaires', [
            'livre_id'  => $this->livre->id,
            'statut_id' => $this->statut->id,
        ]);
    }

    public function test_creation_echoue_sans_livre(): void
    {
        $this->actingAs($this->biblio)->post('/bo/exemplaire/ajout', [
            'statut_id'       => $this->statut->id,
            'mise_en_service' => '2024-01-15',
        ])->assertSessionHasErrors('livre_id');
    }

    public function test_un_bibliothecaire_peut_modifier_un_exemplaire(): void
    {
        $nouveauStatut = Statut::create(['statut' => 'abîmé']);
        $exemplaire = Exemplaire::factory()->create([
            'livre_id'        => $this->livre->id,
            'statut_id'       => $this->statut->id,
            'mise_en_service' => '2023-01-01',
        ]);

        $this->actingAs($this->biblio)->put("/bo/exemplaire/modification/{$exemplaire->id}", [
            'livre_id'        => $this->livre->id,
            'statut_id'       => $nouveauStatut->id,
            'mise_en_service' => '2023-01-01',
        ])->assertRedirect(route('bo.exemplaires'));

        $this->assertEquals($nouveauStatut->id, $exemplaire->fresh()->statut_id);
    }

    public function test_un_bibliothecaire_peut_supprimer_un_exemplaire(): void
    {
        $exemplaire = Exemplaire::factory()->create([
            'livre_id'        => $this->livre->id,
            'statut_id'       => $this->statut->id,
            'mise_en_service' => '2023-01-01',
        ]);

        $this->actingAs($this->biblio)
            ->delete("/bo/exemplaire/suppression/{$exemplaire->id}")
            ->assertRedirect(route('bo.exemplaires'));

        $this->assertDatabaseMissing('exemplaires', ['id' => $exemplaire->id]);
    }

    public function test_un_usager_ne_peut_pas_acceder_au_crud_exemplaires(): void
    {
        $usager = User::factory()->create(['role' => 'usager']);

        $this->actingAs($usager)->get('/bo/exemplaires')->assertStatus(403);
        $this->actingAs($usager)->get('/bo/exemplaire/ajout')->assertStatus(403);
    }
}
