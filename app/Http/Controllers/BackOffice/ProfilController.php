<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use App\Models\User;

class ProfilController extends Controller
{
    public function index()
    {
        $usagers = User::where('role', 'usager')
            ->withCount('emprunts')
            ->orderBy('name')
            ->paginate(20);

        return view('bo.profils.index', compact('usagers'));
    }

    public function show(User $user)
    {
        $user->load([
            'emprunts' => fn ($q) => $q->with('exemplaires.livre')->latest(),
        ]);

        return view('bo.profils.show', compact('user'));
    }

    public function validerRetour(Emprunt $emprunt)
    {
        if ($emprunt->estRendu()) {
            return back()->withErrors(['retour' => 'Cet emprunt a déjà été validé.']);
        }

        $statutDisponible = \App\Models\Statut::where('statut', 'disponible')->firstOrFail();

        $emprunt->update(['date_retour_effective' => now()]);
        $emprunt->exemplaires()->update(['statut_id' => $statutDisponible->id]);

        return back()->with('success', 'Retour validé avec succès.');
    }
}
