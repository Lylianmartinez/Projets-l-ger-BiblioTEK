<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsagerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'usager')
            ->withCount([
                'emprunts as emprunts_en_cours_count' => fn ($q) => $q->whereNull('date_retour_effective'),
            ]);

        if ($search = $request->input('q')) {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            );
        }

        $usagers = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('bo.usagers.index', compact('usagers', 'search'));
    }

    public function show(User $usager)
    {
        $usager->load([
            'emprunts' => fn ($q) => $q->with('exemplaires.livre')->latest(),
        ]);

        return view('bo.usagers.show', compact('usager'));
    }

    public function edit(User $usager)
    {
        return view('bo.usagers.edit', compact('usager'));
    }

    public function update(Request $request, User $usager)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($usager->id)],
            'role'  => ['required', Rule::in(['usager', 'bibliothecaire'])],
        ]);

        $usager->update($data);

        return redirect()->route('bo.usagers.show', $usager)
            ->with('success', 'Compte mis à jour avec succès.');
    }

    public function toggleSuspend(User $usager)
    {
        $usager->update(['is_active' => !$usager->is_active]);

        $message = $usager->is_active ? 'Compte réactivé.' : 'Compte suspendu.';

        return back()->with('success', $message);
    }

    public function destroy(User $usager)
    {
        $empruntsEnCours = $usager->emprunts()->whereNull('date_retour_effective')->count();

        if ($empruntsEnCours > 0) {
            return back()->with('error', "Impossible de supprimer : cet usager a {$empruntsEnCours} emprunt(s) en cours.");
        }

        $usager->delete();

        return redirect()->route('bo.usagers.index')
            ->with('success', 'Compte supprimé.');
    }
}
