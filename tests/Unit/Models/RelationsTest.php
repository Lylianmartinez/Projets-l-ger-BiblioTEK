<?php

namespace Tests\Unit\Models;

use App\Models\Categorie;
use App\Models\Livre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

class RelationsTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    public function test_un_livre_appartient_a_un_auteur(): void
    {
        $livre = Livre::factory()->create();
        $this->assertNotNull($livre->auteur);
        $this->assertSame($livre->auteur_id, $livre->auteur->id);
    }

    public function test_un_livre_peut_avoir_plusieurs_categories(): void
    {
        $livre = Livre::factory()->create();
        $livre->categories()->attach(Categorie::factory()->count(2)->create());

        $this->assertCount(2, $livre->fresh()->categories);
    }

    public function test_exemplaires_disponibles_filtre_sur_le_statut(): void
    {
        $statuts = $this->seedStatuts();
        $livre   = Livre::factory()->create();

        $this->exemplaire($statuts['disponible'], ['livre_id' => $livre->id]);
        $this->exemplaire($statuts['disponible'], ['livre_id' => $livre->id]);
        $this->exemplaire($statuts['emprunté'],   ['livre_id' => $livre->id]);

        $this->assertCount(3, $livre->exemplaires);
        $this->assertCount(2, $livre->exemplairesDisponibles);
    }

    public function test_estDisponible_reflete_le_statut_de_lexemplaire(): void
    {
        $statuts = $this->seedStatuts();

        $this->assertTrue($this->exemplaire($statuts['disponible'])->estDisponible());
        $this->assertFalse($this->exemplaire($statuts['emprunté'])->estDisponible());
    }

    public function test_les_roles_sont_correctement_identifies(): void
    {
        $this->assertTrue($this->usager()->estUsager());
        $this->assertFalse($this->usager()->estBibliothecaire());
        $this->assertTrue($this->bibliothecaire()->estBibliothecaire());
        $this->assertFalse($this->bibliothecaire()->estUsager());
    }

    public function test_emprunt_actif_renvoie_seulement_un_emprunt_non_rendu(): void
    {
        $usager = $this->usager();
        $this->assertNull($usager->empruntActif());

        $emprunt = $this->empruntActif($usager);
        $this->assertNotNull($usager->fresh()->empruntActif());
        $this->assertSame($emprunt->id, $usager->fresh()->empruntActif()->id);

        $emprunt->update(['date_retour_effective' => now()]);
        $this->assertNull($usager->fresh()->empruntActif());
    }
}
