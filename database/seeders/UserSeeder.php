<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Bibliothécaires fixes
        User::firstOrCreate(
            ['email' => 'admin@bibliotek.fr'],
            [
                'name'     => 'Admin Bibliothèque',
                'password' => Hash::make('password'),
                'role'     => 'bibliothecaire',
            ]
        );
        User::factory()->bibliothecaire()->count(2)->create();

        // Usagers
        User::factory()->count(25)->create();
    }
}
