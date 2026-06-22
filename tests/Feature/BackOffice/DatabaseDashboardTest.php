<?php

namespace Tests\Feature\BackOffice;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

/**
 * Dashboard base de données (style phpLiteAdmin) servi par Laravel et protégé
 * par role:bibliothecaire. Exécution SQL arbitraire — couverture lecture,
 * écriture, gestion d'erreur et contrôle d'accès.
 */
class DatabaseDashboardTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    public function test_le_dashboard_liste_les_tables(): void
    {
        $this->actingAs($this->bibliothecaire())
            ->get('/bo/database')
            ->assertOk()
            ->assertViewHas('tables', fn ($tables) => in_array('users', $tables, true));
    }

    public function test_le_parcours_dune_table_renvoie_ses_lignes(): void
    {
        $this->usager(['email' => 'parcouru@example.com']);

        $this->actingAs($this->bibliothecaire())
            ->get('/bo/database?table=users')
            ->assertOk()
            ->assertViewHas('table', 'users')
            ->assertViewHas('columns', fn ($cols) => in_array('email', $cols, true));
    }

    public function test_une_table_inconnue_est_ignoree(): void
    {
        $this->actingAs($this->bibliothecaire())
            ->get('/bo/database?table=table_qui_nexiste_pas')
            ->assertOk()
            ->assertViewHas('table', null);
    }

    public function test_une_requete_select_renvoie_un_jeu_de_resultats(): void
    {
        $this->usager(['email' => 'cible@example.com']);

        $this->actingAs($this->bibliothecaire())
            ->post('/bo/database/query', ['sql' => 'SELECT email FROM users'])
            ->assertOk()
            ->assertViewHas('result', fn ($result) => collect($result)->pluck('email')->contains('cible@example.com'))
            ->assertViewHas('error', null);
    }

    public function test_une_requete_decriture_renvoie_le_nombre_de_lignes_affectees(): void
    {
        $usager = $this->usager(['name' => 'Avant']);

        $this->actingAs($this->bibliothecaire())
            ->post('/bo/database/query', [
                'sql' => "UPDATE users SET name = 'Apres' WHERE id = {$usager->id}",
            ])
            ->assertOk()
            ->assertViewHas('affected', 1)
            ->assertViewHas('result', null);

        $this->assertSame('Apres', $usager->fresh()->name);
    }

    public function test_une_requete_invalide_affiche_lerreur_sans_planter(): void
    {
        $this->actingAs($this->bibliothecaire())
            ->post('/bo/database/query', ['sql' => 'SELECT * FROM nimporte_quoi'])
            ->assertOk()
            ->assertViewHas('error', fn ($error) => is_string($error) && $error !== '');
    }

    public function test_un_usager_ne_peut_pas_acceder_au_dashboard(): void
    {
        $this->actingAs($this->usager())->get('/bo/database')->assertForbidden();
        $this->actingAs($this->usager())
            ->post('/bo/database/query', ['sql' => 'SELECT 1'])
            ->assertForbidden();
    }
}
