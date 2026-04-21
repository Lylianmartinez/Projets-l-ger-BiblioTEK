<?php

namespace Tests\Unit;

use App\Models\Emprunt;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class EmpruntUnitTest extends TestCase
{
    private function makeEmprunt(array $attrs = []): Emprunt
    {
        $emprunt = new Emprunt();
        $emprunt->date_emprunt = Carbon::parse($attrs['date_emprunt'] ?? now());
        $emprunt->date_retour_prevue = Carbon::parse($attrs['date_retour_prevue'] ?? now()->addDays(30));
        $emprunt->date_retour_effective = isset($attrs['date_retour_effective'])
            ? Carbon::parse($attrs['date_retour_effective'])
            : null;

        return $emprunt;
    }

    public function test_un_emprunt_non_rendu_nest_pas_rendu(): void
    {
        $emprunt = $this->makeEmprunt(['date_retour_effective' => null]);
        $this->assertFalse($emprunt->estRendu());
    }

    public function test_un_emprunt_avec_date_retour_est_rendu(): void
    {
        $emprunt = $this->makeEmprunt(['date_retour_effective' => now()]);
        $this->assertTrue($emprunt->estRendu());
    }

    public function test_un_emprunt_depasse_est_en_retard(): void
    {
        $emprunt = $this->makeEmprunt([
            'date_emprunt'       => now()->subDays(40),
            'date_retour_prevue' => now()->subDays(10),
            'date_retour_effective' => null,
        ]);
        $this->assertTrue($emprunt->estEnRetard());
    }

    public function test_un_emprunt_non_depasse_nest_pas_en_retard(): void
    {
        $emprunt = $this->makeEmprunt([
            'date_emprunt'       => now(),
            'date_retour_prevue' => now()->addDays(30),
            'date_retour_effective' => null,
        ]);
        $this->assertFalse($emprunt->estEnRetard());
    }

    public function test_un_emprunt_rendu_nest_pas_en_retard_meme_si_depasse(): void
    {
        $emprunt = $this->makeEmprunt([
            'date_emprunt'          => now()->subDays(40),
            'date_retour_prevue'    => now()->subDays(10),
            'date_retour_effective' => now()->subDays(5),
        ]);
        $this->assertFalse($emprunt->estEnRetard());
    }
}
