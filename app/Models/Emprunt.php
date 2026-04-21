<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Emprunt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
    ];

    protected function casts(): array
    {
        return [
            'date_emprunt'           => 'date',
            'date_retour_prevue'     => 'date',
            'date_retour_effective'  => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exemplaires(): BelongsToMany
    {
        return $this->belongsToMany(Exemplaire::class, 'emprunt_exemplaire');
    }

    public function estRendu(): bool
    {
        return !is_null($this->date_retour_effective);
    }

    public function estEnRetard(): bool
    {
        return !$this->estRendu() && now()->isAfter($this->date_retour_prevue);
    }
}
