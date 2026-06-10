<?php

namespace Tests\Feature;

use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpruntTest extends TestCase
{
    use RefreshDatabase;

    private User $usager;
    private Statut $statutDispo;
    private Statut $statutEmprunte;

    protected function setUp(): void
    {
        parent::setUp();

        $this->statutDispo    = Statut::create(['statut' => 'disponible']);
        $this->statutEmprunte = Statut::create(['statut' => 'emprunté']);
        $this->usager = User::factory()->create(['role' => 'usager']);
    }

    private function creerExemplaire(): Exemplaire
    {
        $livre = Livre::factory()->create();
        return Exemplaire::factory()->create([
            'livre_id'        => $livre->id,
            'statut_id'       => $this->statutDispo->id,
            'mise_en_service' => now()->subYear(),
        ]);
    }

    public function test_la_page_emprunter_est_accessible_pour_un_usager(): void
    {
        $this->actingAs($this->usager)->get('/emprunter')->assertStatus(200);
    }

    public function test_un_usager_peut_emprunter_des_exemplaires(): void
    {
        $exemplaire = $this->creerExemplaire();

        $response = $this->actingAs($this->usager)->post('/emprunter', [
            'exemplaires' => [$exemplaire->id],
        ]);

        $response->assertRedirect(route('profil'));
        $this->assertDatabaseHas('emprunts', ['user_id' => $this->usager->id]);
        $this->assertDatabaseHas('emprunt_exemplaire', ['exemplaire_id' => $exemplaire->id]);
    }

    public function test_la_date_de_retour_prevue_est_30_jours_apres_emprunt(): void
    {
        $exemplaire = $this->creerExemplaire();

        $this->actingAs($this->usager)->post('/emprunter', [
            'exemplaires' => [$exemplaire->id],
        ]);

        $emprunt = $this->usager->emprunts()->latest()->first();
        $this->assertEquals(
            $emprunt->date_emprunt->addDays(30)->toDateString(),
            $emprunt->date_retour_prevue->toDateString()
        );
    }

    public function test_un_usager_ne_peut_pas_emprunter_avec_un_emprunt_actif(): void
    {
        $exemplaire1 = $this->creerExemplaire();
        $exemplaire2 = $this->creerExemplaire();

        // Premier emprunt
        $this->actingAs($this->usager)->post('/emprunter', [
            'exemplaires' => [$exemplaire1->id],
        ]);

        // Tentative de second emprunt
        $response = $this->actingAs($this->usager)->post('/emprunter', [
            'exemplaires' => [$exemplaire2->id],
        ]);

        $response->assertSessionHasErrors('emprunt');
        $this->assertCount(1, $this->usager->emprunts()->get());
    }

    public function test_un_exemplaire_indisponible_ne_peut_pas_etre_emprunte(): void
    {
        $exemplaire = $this->creerExemplaire();
        $exemplaire->update(['statut_id' => $this->statutEmprunte->id]);

        $this->actingAs($this->usager)->post('/emprunter', [
            'exemplaires' => [$exemplaire->id],
        ])->assertSessionHasErrors('emprunt');
    }

    public function test_le_statut_de_lexemplaire_passe_a_emprunte(): void
    {
        $exemplaire = $this->creerExemplaire();

        $this->actingAs($this->usager)->post('/emprunter', [
            'exemplaires' => [$exemplaire->id],
        ]);

        $this->assertEquals($this->statutEmprunte->id, $exemplaire->fresh()->statut_id);
    }

    public function test_le_detail_emprunt_est_accessible_par_son_proprietaire(): void
    {
        $exemplaire = $this->creerExemplaire();
        $this->actingAs($this->usager)->post('/emprunter', ['exemplaires' => [$exemplaire->id]]);
        $emprunt = $this->usager->emprunts()->first();

        $this->actingAs($this->usager)
            ->get("/emprunt/{$emprunt->id}")
            ->assertStatus(200);
    }

    public function test_un_autre_usager_ne_peut_pas_voir_un_emprunt_qui_ne_lui_appartient_pas(): void
    {
        $exemplaire = $this->creerExemplaire();
        $this->actingAs($this->usager)->post('/emprunter', ['exemplaires' => [$exemplaire->id]]);
        $emprunt = $this->usager->emprunts()->first();

        $autreUsager = User::factory()->create(['role' => 'usager']);

        $this->actingAs($autreUsager)
            ->get("/emprunt/{$emprunt->id}")
            ->assertStatus(403);
    }
}
