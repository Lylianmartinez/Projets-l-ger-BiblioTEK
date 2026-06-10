<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InscriptionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InscriptionController extends Controller
{
    public function create()
    {
        return view('auth.inscription');
    }

    public function store(InscriptionRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'usager',
        ]);

        Auth::login($user);

        return redirect()->route('profil')->with('success', 'Inscription réussie. Bienvenue !');
    }
}
