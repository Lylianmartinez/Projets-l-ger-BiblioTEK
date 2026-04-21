<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpruntRequest;
use App\Models\Emprunt;
use App\Models\Exemplaire;
use App\Models\Statut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EmpruntController extends Controller
{
    public function index(Request $request)
    {
        $query = Exemplaire::with(['livre.auteur', 'statut'])
            ->whereHas('statut', fn ($q) => $q->where('statut', 'disponible'));

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('livre', function ($q2) use ($q) {
                $q2->where('titre', 'like', "%$q%")
                   ->orWhereHas('auteur', fn ($a) => $a->where('nom', 'like', "%$q%"));
            });
        }

        $exemplairesDisponibles = $query->orderBy('id')->paginate(20)->withQueryString();

        return view('emprunts.index', compact('exemplairesDisponibles'));
    }

    public function store(EmpruntRequest $request)
    {
        $user = Auth::user();

        // Contrainte : pas d'emprunt actif
        if ($user->empruntActif()) {
            return back()->withErrors(['emprunt' => 'Vous avez déjà un emprunt en cours. Retournez vos exemplaires avant d\'en emprunter de nouveaux.']);
        }

        $statutEmprunte = Statut::where('statut', 'emprunté')->firstOrFail();
        $exemplaires    = Exemplaire::whereIn('id', $request->exemplaires)
            ->whereHas('statut', fn ($q) => $q->where('statut', 'disponible'))
            ->get();

        if ($exemplaires->isEmpty()) {
            return back()->withErrors(['emprunt' => 'Aucun exemplaire disponible sélectionné.']);
        }

        $dateEmprunt = now();
        $emprunt = Emprunt::create([
            'user_id'            => $user->id,
            'date_emprunt'       => $dateEmprunt,
            'date_retour_prevue' => $dateEmprunt->copy()->addDays(30),
        ]);

        $emprunt->exemplaires()->attach($exemplaires->pluck('id')->toArray());
        $exemplaires->each(fn ($e) => $e->update(['statut_id' => $statutEmprunte->id]));

        return redirect()->route('profil')->with('success', 'Emprunt enregistré. Date de retour prévue : ' . $emprunt->date_retour_prevue->format('d/m/Y'));
    }

    public function show(Emprunt $emprunt)
    {
        Gate::authorize('view', $emprunt);

        $emprunt->load(['exemplaires.livre.auteur', 'user']);

        return view('emprunts.show', compact('emprunt'));
    }
}
