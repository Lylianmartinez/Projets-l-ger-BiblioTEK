<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpruntFactory extends Factory
{
    public function definition(): array
    {
        $dateEmprunt = $this->faker->dateTimeBetween('-2 years', '-31 days');

        return [
            'user_id'              => User::where('role', 'usager')->inRandomOrder()->first()?->id ?? User::factory(),
            'date_emprunt'         => $dateEmprunt,
            'date_retour_prevue'   => (clone $dateEmprunt)->modify('+30 days'),
            'date_retour_effective'=> $this->faker->boolean(75)
                ? $this->faker->dateTimeBetween($dateEmprunt, 'now')
                : null,
        ];
    }
}
