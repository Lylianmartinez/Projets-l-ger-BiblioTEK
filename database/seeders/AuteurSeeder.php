<?php

namespace Database\Seeders;

use App\Models\Auteur;
use Illuminate\Database\Seeder;

class AuteurSeeder extends Seeder
{
    public function run(): void
    {
        $auteurs = [
            'Victor Hugo',
            'Émile Zola',
            'Honoré de Balzac',
            'Gustave Flaubert',
            'Charles Baudelaire',
            'Albert Camus',
            'Fiodor Dostoïevski',
            'Franz Kafka',
            'Charles Dickens',
            'Léon Tolstoï',
            'Miguel de Cervantes',
            'George Orwell',
            'Aldous Huxley',
            'Michel Houellebecq',
            'Annie Ernaux',
            'Amélie Nothomb',
            'Patrick Modiano',
            'Romain Gary',
            'J. R. R. Tolkien',
            'Isaac Asimov',
            'Frank Herbert',
            'J. K. Rowling',
            'Agatha Christie',
            'Arthur Conan Doyle',
            'Dan Brown',
            'Gillian Flynn',
            'Yuval Noah Harari',
            'Sun Tzu',
            'Stephen Covey',
            'Antoine de Saint-Exupéry',
            // Nouveaux auteurs (catégories manquantes)
            'René Goscinny',
            'Katsuhiro Otomo',
            'Ernst H. Gombrich',
            'Auguste Escoffier',
            'Jules Verne',
            'Robert C. Martin',
            'Montesquieu',
            'Adam Smith',
        ];

        foreach ($auteurs as $nom) {
            Auteur::firstOrCreate(['nom' => $nom]);
        }
    }
}
