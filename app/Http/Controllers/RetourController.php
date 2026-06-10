<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RetourController extends Controller
{
    public function index()
    {
        $empruntActif = Auth::user()->empruntActif()?->load('exemplaires.livre');

        return view('retours.index', compact('empruntActif'));
    }

    public function store(Emprunt $emprunt)
    {
        Gate::authorize('view', $emprunt);

        if ($emprunt->estRendu()) {
            return back()->withErrors(['retour' => 'Cet emprunt a déjà été retourné.']);
        }

        // L'usager signale le dépôt — le bibliothécaire valide ensuite
        return redirect()->route('profil')->with('info', 'Votre demande de retour a été enregistrée. Un bibliothécaire la validera.');
    }
}
