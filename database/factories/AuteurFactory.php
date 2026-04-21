<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AuteurFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => $this->faker->name(),
        ];
    }
}
