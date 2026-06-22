<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InscriptionValidationTest extends TestCase
{
    use RefreshDatabase;

    private function donnees(array $override = []): array
    {
        return array_merge([
            'name'                  => 'Alice Martin',
            'email'                 => 'alice@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ], $override);
    }

    public function test_inscription_valide_cree_un_usager_actif_et_connecte(): void
    {
        $this->post('/inscription', $this->donnees())
            ->assertRedirect(route('profil'));

        $user = User::where('email', 'alice@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('usager', $user->role);
        $this->assertTrue((bool) $user->is_active);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_le_nom_est_obligatoire(): void
    {
        $this->post('/inscription', $this->donnees(['name' => '']))
            ->assertSessionHasErrors('name');
    }

    public function test_email_invalide_est_rejete(): void
    {
        $this->post('/inscription', $this->donnees(['email' => 'pas-un-email']))
            ->assertSessionHasErrors('email');
    }

    public function test_email_doit_etre_unique(): void
    {
        User::factory()->create(['email' => 'alice@example.com']);

        $this->post('/inscription', $this->donnees())
            ->assertSessionHasErrors('email');
    }

    public function test_mot_de_passe_trop_court_est_rejete(): void
    {
        $this->post('/inscription', $this->donnees([
            'password'              => 'court',
            'password_confirmation' => 'court',
        ]))->assertSessionHasErrors('password');
    }

    public function test_confirmation_de_mot_de_passe_doit_correspondre(): void
    {
        $this->post('/inscription', $this->donnees([
            'password_confirmation' => 'different123',
        ]))->assertSessionHasErrors('password');

        $this->assertGuest();
    }
}
