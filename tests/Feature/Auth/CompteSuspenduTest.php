<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

class CompteSuspenduTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    public function test_un_compte_suspendu_ne_peut_pas_se_connecter(): void
    {
        User::factory()->create([
            'email'     => 'suspendu@example.com',
            'password'  => 'password',
            'is_active' => false,
        ]);

        $this->post('/connexion', [
            'email'    => 'suspendu@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_un_usager_actif_suspendu_pendant_sa_session_est_deconnecte(): void
    {
        $usager = $this->usager();

        // Accès normal d'abord.
        $this->actingAs($usager)->get('/profil')->assertOk();

        // Suspension côté back-office puis nouvelle requête : déconnexion + redirection.
        $usager->update(['is_active' => false]);

        $this->actingAs($usager)
            ->get('/profil')
            ->assertRedirect(route('connexion'))
            ->assertSessionHasErrors('email');
    }
}
