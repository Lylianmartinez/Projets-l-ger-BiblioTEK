<?php

namespace App\Http\Controllers;

use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Livre;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $auteurs    = Auteur::orderBy('nom')->get();
        $categories = Categorie::orderBy('categorie')->get();

        $query = Livre::with(['auteur', 'categories', 'exemplaires.statut']);

        if ($request->filled('titre')) {
            $query->where('titre', 'like', '%' . $request->titre . '%');
        }

        if ($request->filled('auteur_id')) {
            $query->where('auteur_id', $request->auteur_id);
        }

        if ($request->filled('categorie_id')) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $request->categorie_id));
        }

        if ($request->filled('disponible')) {
            $query->whereHas('exemplaires', fn ($q) => $q->whereHas('statut', fn ($s) => $s->where('statut', 'disponible')));
        }

        $livres = $query->orderBy('titre')->paginate(20)->withQueryString();

        return view('recherche.index', compact('livres', 'auteurs', 'categories'));
    }
}
