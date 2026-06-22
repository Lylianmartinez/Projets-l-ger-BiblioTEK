<?php

namespace Tests\Unit;

use App\Models\Emprunt;
use App\Models\User;
use App\Policies\EmpruntPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsLibrary;
use Tests\TestCase;

class EmpruntPolicyTest extends TestCase
{
    use RefreshDatabase, SeedsLibrary;

    public function test_le_proprietaire_peut_consulter_son_emprunt(): void
    {
        $usager  = $this->usager();
        $emprunt = $this->empruntActif($usager);

        $this->assertTrue((new EmpruntPolicy)->view($usager, $emprunt));
    }

    public function test_un_autre_usager_ne_peut_pas_consulter_lemprunt(): void
    {
        $emprunt = $this->empruntActif($this->usager());
        $intrus  = $this->usager();

        $this->assertFalse((new EmpruntPolicy)->view($intrus, $emprunt));
    }

    public function test_un_bibliothecaire_peut_consulter_nimporte_quel_emprunt(): void
    {
        $emprunt = $this->empruntActif($this->usager());

        $this->assertTrue((new EmpruntPolicy)->view($this->bibliothecaire(), $emprunt));
    }
}
