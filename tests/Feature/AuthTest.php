<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_inscription_affiche_le_formulaire(): void
    {
        $this->get('/inscription')->assertStatus(200)->assertSee('Inscription');
    }

    public function test_un_usager_peut_sinscrire(): void
    {
        $response = $this->post('/inscription', [
            'name'                  => 'Jean Dupont',
            'email'                 => 'jean@test.fr',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('profil'));
        $this->assertDatabaseHas('users', ['email' => 'jean@test.fr', 'role' => 'usager']);
    }

    public function test_inscription_echoue_avec_email_deja_utilise(): void
    {
        User::factory()->create(['email' => 'jean@test.fr']);

        $this->post('/inscription', [
            'name'                  => 'Jean Dupont',
            'email'                 => 'jean@test.fr',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_connexion_affiche_le_formulaire(): void
    {
        $this->get('/connexion')->assertStatus(200)->assertSee('Connexion');
    }

    public function test_un_usager_peut_se_connecter(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $this->post('/connexion', [
            'email'    => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('profil'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_un_bibliothecaire_est_redirige_vers_le_backoffice(): void
    {
        $biblio = User::factory()->bibliothecaire()->create(['password' => bcrypt('password123')]);

        $this->post('/connexion', [
            'email'    => $biblio->email,
            'password' => 'password123',
        ])->assertRedirect(route('bo.profils'));
    }

    public function test_connexion_echoue_avec_mauvais_mot_de_passe(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $this->post('/connexion', [
            'email'    => $user->email,
            'password' => 'mauvais',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_deconnexion(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/deconnexion')
            ->assertRedirect(route('connexion'));

        $this->assertGuest();
    }

    public function test_le_profil_est_inaccessible_sans_connexion(): void
    {
        $this->get('/profil')->assertRedirect(route('connexion'));
    }

    public function test_un_usager_ne_peut_pas_acceder_au_backoffice(): void
    {
        $user = User::factory()->create(['role' => 'usager']);

        $this->actingAs($user)->get('/bo/profils')->assertStatus(403);
    }

    public function test_un_bibliothecaire_ne_peut_pas_acceder_au_profil_usager(): void
    {
        $biblio = User::factory()->bibliothecaire()->create();

        $this->actingAs($biblio)->get('/profil')->assertStatus(403);
    }
}
