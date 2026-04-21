<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function estUsager(): bool
    {
        return $this->role === 'usager';
    }

    public function estBibliothecaire(): bool
    {
        return $this->role === 'bibliothecaire';
    }

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }

    public function empruntActif(): ?Emprunt
    {
        return $this->emprunts()
            ->whereNull('date_retour_effective')
            ->latest()
            ->first();
    }
}
