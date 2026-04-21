<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategorieFactory extends Factory
{
    private static array $categories = [
        'Roman', 'Science-fiction', 'Fantasy', 'Policier', 'Thriller',
        'Biographie', 'Histoire', 'Sciences', 'Philosophie', 'Poésie',
        'Jeunesse', 'Bande dessinée', 'Manga', 'Développement personnel',
        'Art', 'Cuisine', 'Voyage', 'Informatique', 'Droit', 'Économie',
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $cat = self::$categories[self::$index % count(self::$categories)];
        self::$index++;

        return ['categorie' => $cat];
    }
}
