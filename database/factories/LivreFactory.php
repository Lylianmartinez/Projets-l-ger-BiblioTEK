<?php

namespace Database\Factories;

use App\Models\Auteur;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titre'     => $this->faker->sentence(rand(2, 6), false),
            'auteur_id' => Auteur::inRandomOrder()->first()?->id ?? Auteur::factory(),
        ];
    }
}
