<?php

namespace App\Policies;

use App\Models\Emprunt;
use App\Models\User;

class EmpruntPolicy
{
    public function view(User $user, Emprunt $emprunt): bool
    {
        return $user->id === $emprunt->user_id || $user->estBibliothecaire();
    }
}
