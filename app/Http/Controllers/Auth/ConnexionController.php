<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConnexionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnexionController extends Controller
{
    public function create()
    {
        return view('auth.connexion');
    }

    public function store(ConnexionRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return $this->echecConnexion('Ces identifiants ne correspondent à aucun compte.');
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->echecConnexion('Votre compte a été suspendu. Contactez la bibliothèque.');
        }

        $user->update(['derniere_connexion' => now()]);

        return $user->estBibliothecaire()
            ? redirect()->route('bo.profils')
            : redirect()->intended(route('profil'));
    }

    private function echecConnexion(string $message)
    {
        return back()->withErrors([
            'email' => $message,
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('connexion');
    }
}
