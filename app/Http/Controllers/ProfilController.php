<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load([
            'emprunts' => fn ($q) => $q->with('exemplaires.livre')->latest(),
        ]);

        $empruntActif = $user->emprunts->firstWhere('date_retour_effective', null);
        $historique   = $user->emprunts->whereNotNull('date_retour_effective')->values();

        return view('profil.index', compact('user', 'empruntActif', 'historique'));
    }
}
