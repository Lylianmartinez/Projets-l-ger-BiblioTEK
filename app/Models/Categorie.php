<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['categorie'];

    public function livres(): BelongsToMany
    {
        return $this->belongsToMany(Livre::class, 'livres_categories');
    }
}
