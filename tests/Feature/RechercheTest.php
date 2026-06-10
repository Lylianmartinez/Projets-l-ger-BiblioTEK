<?php

namespace Tests\Feature;

use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RechercheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Statut::create(['statut' => 'disponible']);
        Statut::create(['statut' => 'emprunté']);
    }

    public function test_la_recherche_est_accessible_aux_visiteurs(): void
    {
        $this->get('/recherche')->assertStatus(200);
    }

    public function test_recherche_par_titre(): void
    {
        $auteur  = Auteur::factory()->create();
        $livre1  = Livre::factory()->create(['titre' => 'Le Seigneur des Anneaux', 'auteur_id' => $auteur->id]);
        $livre2  = Livre::factory()->create(['titre' => 'Harry Potter', 'auteur_id' => $auteur->id]);

        $response = $this->get('/recherche?titre=Seigneur');

        $response->assertStatus(200)
            ->assertSee('Le Seigneur des Anneaux')
            ->assertDontSee('Harry Potter');
    }

    public function test_recherche_par_auteur(): void
    {
        $auteur1 = Auteur::factory()->create(['nom' => 'Tolkien']);
        $auteur2 = Auteur::factory()->create(['nom' => 'Rowling']);
        $livre1  = Livre::factory()->create(['titre' => 'Livre Tolkien', 'auteur_id' => $auteur1->id]);
        $livre2  = Livre::factory()->create(['titre' => 'Livre Rowling', 'auteur_id' => $auteur2->id]);

        $response = $this->get("/recherche?auteur_id={$auteur1->id}");

        $response->assertStatus(200)
            ->assertSee('Livre Tolkien')
            ->assertDontSee('Livre Rowling');
    }

    public function test_recherche_par_categorie(): void
    {
        $auteur = Auteur::factory()->create();
        $cat1   = Categorie::create(['categorie' => 'Roman']);
        $cat2   = Categorie::create(['categorie' => 'Science-fiction']);

        $livre1 = Livre::factory()->create(['auteur_id' => $auteur->id, 'titre' => 'Roman classique']);
        $livre1->categories()->attach($cat1->id);

        $livre2 = Livre::factory()->create(['auteur_id' => $auteur->id, 'titre' => 'Dune']);
        $livre2->categories()->attach($cat2->id);

        $response = $this->get("/recherche?categorie_id={$cat1->id}");

        $response->assertStatus(200)
            ->assertSee('Roman classique')
            ->assertDontSee('Dune');
    }

    public function test_recherche_disponibilite(): void
    {
        $dispo   = Statut::where('statut', 'disponible')->first();
        $emprunte= Statut::where('statut', 'emprunté')->first();
        $auteur  = Auteur::factory()->create();

        $livre1 = Livre::factory()->create(['auteur_id' => $auteur->id, 'titre' => 'Disponible']);
        Exemplaire::factory()->create([
            'livre_id' => $livre1->id, 'statut_id' => $dispo->id, 'mise_en_service' => now(),
        ]);

        $livre2 = Livre::factory()->create(['auteur_id' => $auteur->id, 'titre' => 'Indisponible']);
        Exemplaire::factory()->create([
            'livre_id' => $livre2->id, 'statut_id' => $emprunte->id, 'mise_en_service' => now(),
        ]);

        $response = $this->get('/recherche?disponible=1');

        $response->assertStatus(200)
            ->assertSee('Disponible')
            ->assertDontSee('Indisponible');
    }
}
