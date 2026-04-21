<?php

namespace Database\Seeders;

use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Livre;
use Illuminate\Database\Seeder;

class LivreSeeder extends Seeder
{
    public function run(): void
    {
        // [titre, auteur, [catégories], isbn]
        $livres = [
            // ── Classiques français
            ['Les Misérables',              'Victor Hugo',               ['Roman', 'Histoire'],         '9780140444308'],
            ['Germinal',                    'Émile Zola',                ['Roman'],                     '9780140447422'],
            ['Le Père Goriot',              'Honoré de Balzac',          ['Roman'],                     '9780140443738'],
            ['Madame Bovary',               'Gustave Flaubert',          ['Roman'],                     '9780140449129'],
            ['Les Fleurs du Mal',           'Charles Baudelaire',        ['Poésie'],                    '9780140447071'],
            ["L'Étranger",                  'Albert Camus',              ['Roman', 'Philosophie'],      '9780140186925'],
            ['Le Petit Prince',             'Antoine de Saint-Exupéry',  ['Roman', 'Jeunesse'],         '9780156012195'],

            // ── Classiques étrangers
            ['Crime et Châtiment',          'Fiodor Dostoïevski',        ['Roman'],                     '9780140449136'],
            ['La Métamorphose',             'Franz Kafka',               ['Roman', 'Philosophie'],      '9780140186888'],
            ['Oliver Twist',                'Charles Dickens',           ['Roman'],                     '9780141439747'],
            ['Anna Karénine',               'Léon Tolstoï',              ['Roman'],                     '9780140449174'],
            ['Don Quichotte',               'Miguel de Cervantes',       ['Roman', 'Philosophie'],      '9780060934347'],

            // ── Littérature contemporaine
            ['Les Particules élémentaires', 'Michel Houellebecq',        ['Roman'],                     '9782290119891'],
            ['La Place',                    'Annie Ernaux',              ['Roman', 'Biographie'],       '9782070415724'],
            ['Stupeur et Tremblements',     'Amélie Nothomb',            ['Roman'],                     '9782253151111'],
            ["La Place de l'Étoile",        'Patrick Modiano',           ['Roman'],                     '9782070368396'],
            ["La Promesse de l'Aube",       'Romain Gary',               ['Roman', 'Biographie'],       '9782070368228'],

            // ── Fantasy & Science-fiction
            ['Le Seigneur des Anneaux',     'J. R. R. Tolkien',          ['Fantasy'],                   '9780547928227'],
            ['Fondation',                   'Isaac Asimov',              ['Science-fiction'],           '9780553293357'],
            ['Dune',                        'Frank Herbert',             ['Science-fiction'],           '9780441013593'],
            ["Harry Potter à l'École des Sorciers", 'J. K. Rowling',    ['Fantasy', 'Jeunesse'],       '9780439708180'],
            ['1984',                        'George Orwell',             ['Science-fiction', 'Roman'],  '9780451524935'],
            ['Le Meilleur des Mondes',      'Aldous Huxley',             ['Science-fiction', 'Roman'],  '9780060850524'],

            // ── Policier & Thriller
            ['Le Meurtre de Roger Ackroyd', 'Agatha Christie',           ['Policier'],                  '9780062073563'],
            ['Les Aventures de Sherlock Holmes', 'Arthur Conan Doyle',   ['Policier'],                  '9780140439076'],
            ['Le Code Da Vinci',            'Dan Brown',                 ['Thriller'],                  '9780307474278'],
            ['Les Apparences',              'Gillian Flynn',             ['Thriller', 'Policier'],      '9780307588364'],

            // ── Essais & Développement personnel
            ['Sapiens',                     'Yuval Noah Harari',         ['Histoire', 'Sciences'],      '9780062316097'],
            ["L'Art de la Guerre",          'Sun Tzu',                   ['Philosophie', 'Histoire'],   '9781590302255'],
            ['Les 7 Habitudes',             'Stephen Covey',             ['Développement personnel'],   '9780743269513'],

            // ── Catégories manquantes
            ['Astérix le Gaulois',          'René Goscinny',             ['Bande dessinée', 'Jeunesse'],'9782012101333'],
            ['Akira',                       'Katsuhiro Otomo',           ['Manga', 'Science-fiction'],  '9781935429005'],
            ["L'Histoire de l'Art",         'Ernst H. Gombrich',         ['Art', 'Histoire'],           '9780714832470'],
            ['Le Guide Culinaire',          'Auguste Escoffier',         ['Cuisine'],                   '9782081229853'],
            ['Le Tour du monde en 80 jours','Jules Verne',               ['Voyage', 'Roman'],           '9782070416820'],
            ['Clean Code',                  'Robert C. Martin',          ['Informatique'],              '9780132350884'],
            ["De l'Esprit des Lois",        'Montesquieu',               ['Droit', 'Philosophie'],      '9782080700360'],
            ['La Richesse des Nations',     'Adam Smith',                ['Économie', 'Histoire'],      '9782070121427'],
        ];

        foreach ($livres as [$titre, $auteurNom, $cats, $isbn]) {
            $auteur = Auteur::where('nom', $auteurNom)->firstOrFail();

            $livre = Livre::create([
                'titre'     => $titre,
                'auteur_id' => $auteur->id,
                'cover_url' => "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg",
            ]);

            $categorieIds = Categorie::whereIn('categorie', $cats)->pluck('id')->toArray();
            $livre->categories()->attach($categorieIds);
        }
    }
}
