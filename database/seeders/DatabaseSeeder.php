<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private const EMILE_ZOLA = 'Émile Zola';

    public function run(): void
    {
        $now = now();

        DB::table('statuts')->insert([
            ['statut' => 'disponible',  'created_at' => $now, 'updated_at' => $now],
            ['statut' => 'emprunté',    'created_at' => $now, 'updated_at' => $now],
            ['statut' => 'réservé',     'created_at' => $now, 'updated_at' => $now],
            ['statut' => 'abîmé',       'created_at' => $now, 'updated_at' => $now],
            ['statut' => 'perdu',       'created_at' => $now, 'updated_at' => $now],
        ]);

        $disponible = DB::table('statuts')->where('statut', 'disponible')->value('id');
        $emprunte   = DB::table('statuts')->where('statut', 'emprunté')->value('id');
        $abime      = DB::table('statuts')->where('statut', 'abîmé')->value('id');

        foreach (['Roman','Science-fiction','Policier','Histoire','Philosophie','Poésie','Biographie','Sciences'] as $c) {
            DB::table('categories')->insert(['categorie' => $c, 'created_at' => $now, 'updated_at' => $now]);
        }
        $catIds = DB::table('categories')->pluck('id', 'categorie');

        foreach ([
            'Victor Hugo','Albert Camus','Simone de Beauvoir','Marcel Proust',
            self::EMILE_ZOLA,'Gustave Flaubert','Stendhal','Honoré de Balzac',
            'Jules Verne','Georges Simenon','Agatha Christie','Franz Kafka',
            'Fyodor Dostoïevski','Leo Tolstoï','Gabriel García Márquez',
            'George Orwell','Ernest Hemingway','Virginia Woolf',
        ] as $nom) {
            DB::table('auteurs')->insert(['nom' => $nom, 'created_at' => $now, 'updated_at' => $now]);
        }
        $auteurIds = DB::table('auteurs')->pluck('id', 'nom');

        $livres = [
            ['Les Misérables',                   'Victor Hugo',            'Roman',          'https://covers.openlibrary.org/b/id/8739161-L.jpg'],
            ['Notre-Dame de Paris',              'Victor Hugo',            'Roman',          'https://covers.openlibrary.org/b/id/8231856-L.jpg'],
            ["L'Étranger",                       'Albert Camus',           'Roman',          'https://covers.openlibrary.org/b/id/8231990-L.jpg'],
            ['La Peste',                         'Albert Camus',           'Roman',          'https://covers.openlibrary.org/b/id/8725942-L.jpg'],
            ['Le Mythe de Sisyphe',              'Albert Camus',           'Philosophie',    'https://covers.openlibrary.org/b/id/1014395-L.jpg'],
            ['Le Deuxième Sexe',                 'Simone de Beauvoir',     'Philosophie',    'https://covers.openlibrary.org/b/id/78169-L.jpg'],
            ['Du côté de chez Swann',            'Marcel Proust',          'Roman',          'https://covers.openlibrary.org/b/id/8231995-L.jpg'],
            ['Germinal',                         self::EMILE_ZOLA,             'Roman',          'https://covers.openlibrary.org/b/id/8231980-L.jpg'],
            ['Nana',                             self::EMILE_ZOLA,             'Roman',          'https://covers.openlibrary.org/b/id/8237804-L.jpg'],
            ['Madame Bovary',                    'Gustave Flaubert',       'Roman',          'https://covers.openlibrary.org/b/id/8231975-L.jpg'],
            ['Le Rouge et le Noir',              'Stendhal',               'Roman',          'https://covers.openlibrary.org/b/id/8231413-L.jpg'],
            ['Le Père Goriot',                   'Honoré de Balzac',       'Roman',          'https://covers.openlibrary.org/b/id/8231970-L.jpg'],
            ['Vingt mille lieues sous les mers', 'Jules Verne',            'Science-fiction','https://covers.openlibrary.org/b/id/8231965-L.jpg'],
            ['Le Tour du monde en 80 jours',     'Jules Verne',            'Roman',          'https://covers.openlibrary.org/b/id/6976035-L.jpg'],
            ['Voyage au centre de la Terre',     'Jules Verne',            'Science-fiction','https://covers.openlibrary.org/b/id/5890987-L.jpg'],
            ['Le Chien jaune',                   'Georges Simenon',        'Policier',       'https://covers.openlibrary.org/b/id/9244876-L.jpg'],
            ['Maigret tend un piège',            'Georges Simenon',        'Policier',       'https://covers.openlibrary.org/b/id/14006662-L.jpg'],
            ['Le Meurtre de Roger Ackroyd',      'Agatha Christie',        'Policier',       'https://covers.openlibrary.org/b/id/8231960-L.jpg'],
            ['Dix Petits Nègres',                'Agatha Christie',        'Policier',       'https://covers.openlibrary.org/b/id/11172296-L.jpg'],
            ['La Métamorphose',                  'Franz Kafka',            'Roman',          'https://covers.openlibrary.org/b/id/8231955-L.jpg'],
            ['Le Procès',                        'Franz Kafka',            'Roman',          'https://covers.openlibrary.org/b/id/14910748-L.jpg'],
            ['Crime et Châtiment',               'Fyodor Dostoïevski',     'Roman',          'https://covers.openlibrary.org/b/id/8231950-L.jpg'],
            ["L'Idiot",                          'Fyodor Dostoïevski',     'Roman',          'https://covers.openlibrary.org/b/id/11532473-L.jpg'],
            ['Guerre et Paix',                   'Leo Tolstoï',            'Histoire',       'https://covers.openlibrary.org/b/id/8231945-L.jpg'],
            ['Anna Karénine',                    'Leo Tolstoï',            'Roman',          'https://covers.openlibrary.org/b/id/12327215-L.jpg'],
            ['Cent ans de solitude',             'Gabriel García Márquez', 'Roman',          'https://covers.openlibrary.org/b/id/8231940-L.jpg'],
            ['1984',                             'George Orwell',          'Science-fiction','https://covers.openlibrary.org/b/id/8575708-L.jpg'],
            ['La Ferme des animaux',             'George Orwell',          'Roman',          'https://covers.openlibrary.org/b/id/11261770-L.jpg'],
            ['Le Vieil Homme et la Mer',         'Ernest Hemingway',       'Roman',          'https://covers.openlibrary.org/b/id/463307-L.jpg'],
            ['Mrs Dalloway',                     'Virginia Woolf',         'Roman',          'https://covers.openlibrary.org/b/id/6397580-L.jpg'],
        ];

        $livreIds = [];
        foreach ($livres as [$titre, $auteur, $cat, $cover]) {
            $uuid = Str::uuid()->toString();
            DB::table('livres')->insert([
                'id' => $uuid, 'titre' => $titre,
                'auteur_id' => $auteurIds[$auteur], 'cover_url' => $cover,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            DB::table('livres_categories')->insert(['livre_id' => $uuid, 'categorie_id' => $catIds[$cat]]);
            $livreIds[$titre] = $uuid;
        }

        $exemplaires = [];
        foreach ($livreIds as $titre => $uuid) {
            $count = in_array($titre, ['Les Misérables','Germinal','1984','La Peste','Crime et Châtiment']) ? 3 : 2;
            for ($i = 0; $i < $count; $i++) {
                $exemplaires[$titre][] = DB::table('exemplaires')->insertGetId([
                    'livre_id' => $uuid, 'statut_id' => $disponible,
                    'mise_en_service' => '2023-09-01',
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        DB::table('users')->insert([
            'name' => 'Marie Lefebvre', 'email' => 'bibliothecaire@bibliotek.fr',
            'role' => 'bibliothecaire', 'is_active' => true,
            'password' => Hash::make('Bibliotek2026!'),
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $usagerIds = [];
        foreach ([
            ['Lucas Martin',   'lucas@example.fr'],
            ['Emma Bernard',   'emma@example.fr'],
            ['Hugo Petit',     'hugo@example.fr'],
            ['Léa Thomas',     'lea@example.fr'],
            ['Nathan Dubois',  'nathan@example.fr'],
            ['Camille Robert', 'camille@example.fr'],
            ['Inès Richard',   'ines@example.fr'],
            ['Théo Simon',     'theo@example.fr'],
        ] as [$name, $email]) {
            $usagerIds[] = DB::table('users')->insertGetId([
                'name' => $name, 'email' => $email,
                'role' => 'usager', 'is_active' => true,
                'password' => Hash::make('password'),
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        DB::table('users')->insert([
            'name' => 'Paul Dupont', 'email' => 'suspendu@example.fr',
            'role' => 'usager', 'is_active' => false,
            'password' => Hash::make('password'),
            'created_at' => $now, 'updated_at' => $now,
        ]);

        foreach ([
            [0, 'Les Misérables',              '2026-05-20', '2026-06-03'],
            [1, 'La Peste',                    '2026-05-25', '2026-06-08'],
            [2, '1984',                        '2026-06-01', '2026-06-15'],
            [3, 'Germinal',                    '2026-06-03', '2026-06-17'],
            [4, 'Crime et Châtiment',          '2026-06-05', '2026-06-19'],
            [5, 'Le Meurtre de Roger Ackroyd', '2026-06-07', '2026-06-21'],
        ] as [$uIdx, $titre, $dateE, $dateR]) {
            $ex = array_shift($exemplaires[$titre]);
            $eid = DB::table('emprunts')->insertGetId([
                'user_id' => $usagerIds[$uIdx], 'date_emprunt' => $dateE,
                'date_retour_prevue' => $dateR, 'date_retour_effective' => null,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            DB::table('emprunt_exemplaire')->insert(['emprunt_id' => $eid, 'exemplaire_id' => $ex]);
            DB::table('exemplaires')->where('id', $ex)->update(['statut_id' => $emprunte]);
        }

        foreach ([
            [0, 'Madame Bovary',       '2026-03-10', '2026-03-24', '2026-03-22'],
            [1, 'La Métamorphose',     '2026-03-15', '2026-03-29', '2026-03-28'],
            [2, 'Le Père Goriot',      '2026-04-01', '2026-04-15', '2026-04-14'],
            [3, 'Guerre et Paix',      '2026-04-10', '2026-04-24', '2026-04-30'],
            [4, 'Anna Karénine',       '2026-04-20', '2026-05-04', '2026-05-02'],
            [0, '1984',                '2026-05-01', '2026-05-15', '2026-05-13'],
            [6, 'Cent ans de solitude','2026-05-05', '2026-05-19', '2026-05-18'],
            [7, 'Notre-Dame de Paris', '2026-05-10', '2026-05-24', '2026-05-24'],
        ] as [$uIdx, $titre, $dateE, $dateR, $dateEff]) {
            $eid = DB::table('emprunts')->insertGetId([
                'user_id' => $usagerIds[$uIdx], 'date_emprunt' => $dateE,
                'date_retour_prevue' => $dateR, 'date_retour_effective' => $dateEff,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            DB::table('emprunt_exemplaire')->insert(['emprunt_id' => $eid, 'exemplaire_id' => $exemplaires[$titre][0]]);
        }

        if (!empty($exemplaires['Guerre et Paix'][1])) {
            DB::table('exemplaires')->where('id', $exemplaires['Guerre et Paix'][1])->update(['statut_id' => $abime]);
        }
    }
}
