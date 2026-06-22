<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutingTest extends TestCase
{
    public function test_la_racine_redirige_vers_la_recherche(): void
    {
        $this->get('/')->assertRedirect(route('recherche'));
    }

    public function test_le_endpoint_de_sante_repond(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_un_visiteur_sur_une_route_protegee_est_redirige_vers_la_connexion(): void
    {
        // Le projet nomme la route de login « connexion » : le middleware auth
        // doit rediriger vers elle (et non lever une RouteNotFoundException 500).
        $this->get('/profil')->assertRedirect(route('connexion'));
        $this->get('/emprunter')->assertRedirect(route('connexion'));
        $this->get('/bo/exemplaires')->assertRedirect(route('connexion'));
    }
}
