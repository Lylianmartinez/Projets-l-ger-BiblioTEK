<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exemplaire extends Model
{
    use HasFactory;

    protected $fillable = ['livre_id', 'statut_id', 'mise_en_service'];

    protected function casts(): array
    {
        return ['mise_en_service' => 'date'];
    }

    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    public function statut(): BelongsTo
    {
        return $this->belongsTo(Statut::class);
    }

    public function emprunts(): BelongsToMany
    {
        return $this->belongsToMany(Emprunt::class, 'emprunt_exemplaire');
    }

    public function estDisponible(): bool
    {
        return $this->statut->statut === 'disponible';
    }
}
