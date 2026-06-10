<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExemplaireRequest;
use App\Models\Exemplaire;
use App\Models\Livre;
use App\Models\Statut;

class ExemplaireController extends Controller
{
    public function index()
    {
        $exemplaires = Exemplaire::with(['livre.auteur', 'statut'])->latest()->paginate(30);

        return view('bo.exemplaires.index', compact('exemplaires'));
    }

    public function create()
    {
        $livres  = Livre::with('auteur')->orderBy('titre')->get();
        $statuts = Statut::all();

        return view('bo.exemplaires.create', compact('livres', 'statuts'));
    }

    public function store(ExemplaireRequest $request)
    {
        Exemplaire::create($request->validated());

        return redirect()->route('bo.exemplaires')->with('success', 'Exemplaire ajouté avec succès.');
    }

    public function edit(Exemplaire $exemplaire)
    {
        $livres  = Livre::with('auteur')->orderBy('titre')->get();
        $statuts = Statut::all();

        return view('bo.exemplaires.edit', compact('exemplaire', 'livres', 'statuts'));
    }

    public function update(ExemplaireRequest $request, Exemplaire $exemplaire)
    {
        $exemplaire->update($request->validated());

        return redirect()->route('bo.exemplaires')->with('success', 'Exemplaire modifié avec succès.');
    }

    public function destroy(Exemplaire $exemplaire)
    {
        $exemplaire->delete();

        return redirect()->route('bo.exemplaires')->with('success', 'Exemplaire supprimé.');
    }
}
