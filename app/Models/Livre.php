<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['titre', 'auteur_id', 'cover_url'];

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(Auteur::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'livres_categories');
    }

    public function exemplaires(): HasMany
    {
        return $this->hasMany(Exemplaire::class);
    }

    public function exemplairesDisponibles(): HasMany
    {
        return $this->hasMany(Exemplaire::class)
            ->whereHas('statut', fn ($q) => $q->where('statut', 'disponible'));
    }
}
