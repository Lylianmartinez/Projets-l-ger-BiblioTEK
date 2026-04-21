<?php

namespace Database\Factories;

use App\Models\Livre;
use App\Models\Statut;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExemplaireFactory extends Factory
{
    public function definition(): array
    {
        return [
            'livre_id'        => Livre::inRandomOrder()->first()?->id ?? Livre::factory(),
            'statut_id'       => Statut::where('statut', 'disponible')->first()?->id ?? 1,
            'mise_en_service' => $this->faker->dateTimeBetween('-10 years', 'now'),
        ];
    }
}
