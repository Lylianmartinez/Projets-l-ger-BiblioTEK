<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Roman', 'Science-fiction', 'Fantasy', 'Policier', 'Thriller',
            'Biographie', 'Histoire', 'Sciences', 'Philosophie', 'Poésie',
            'Jeunesse', 'Bande dessinée', 'Manga', 'Développement personnel',
            'Art', 'Cuisine', 'Voyage', 'Informatique', 'Droit', 'Économie',
        ];

        foreach ($categories as $cat) {
            Categorie::firstOrCreate(['categorie' => $cat]);
        }
    }
}
