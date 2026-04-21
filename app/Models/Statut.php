<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Statut extends Model
{
    protected $fillable = ['statut'];

    public function exemplaires(): HasMany
    {
        return $this->hasMany(Exemplaire::class);
    }
}
