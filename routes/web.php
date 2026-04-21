<?php

use App\Http\Controllers\Admin\UsagerController;
use App\Http\Controllers\Auth\ConnexionController;
use App\Http\Controllers\Auth\InscriptionController;
use App\Http\Controllers\BackOffice\ExemplaireController as BOExemplaireController;
use App\Http\Controllers\BackOffice\ProfilController as BOProfilController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\RetourController;
use Illuminate\Support\Facades\Route;

// Page d'accueil → redirection vers recherche
Route::get('/', fn () => redirect()->route('recherche'));

// ─── Authentification ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [InscriptionController::class, 'create'])->name('inscription');
    Route::post('/inscription', [InscriptionController::class, 'store']);

    Route::get('/connexion', [ConnexionController::class, 'create'])->name('connexion');
    Route::post('/connexion', [ConnexionController::class, 'store']);
});

Route::post('/deconnexion', [ConnexionController::class, 'destroy'])
    ->middleware('auth')
    ->name('deconnexion');

// ─── Recherche (tous les visiteurs) ──────────────────────────────────────────
Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche');

// ─── Usager connecté ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:usager'])->group(function () {
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');

    Route::get('/emprunter', [EmpruntController::class, 'index'])->name('emprunter');
    Route::post('/emprunter', [EmpruntController::class, 'store'])->name('emprunter.store');

    Route::get('/retour', [RetourController::class, 'index'])->name('retour');
    Route::post('/retour/{emprunt}', [RetourController::class, 'store'])->name('retour.store');

    Route::get('/emprunt/{emprunt}', [EmpruntController::class, 'show'])->name('emprunt.show');
});

// ─── Back-office bibliothécaire ───────────────────────────────────────────────
Route::middleware(['auth', 'role:bibliothecaire'])->prefix('bo')->name('bo.')->group(function () {

    Route::get('/profils', [BOProfilController::class, 'index'])->name('profils');
    Route::get('/profil/{user}', [BOProfilController::class, 'show'])->name('profil.show');
    Route::post('/retour/{emprunt}/valider', [BOProfilController::class, 'validerRetour'])->name('retour.valider');

    Route::get('/exemplaires', [BOExemplaireController::class, 'index'])->name('exemplaires');
    Route::get('/exemplaire/ajout', [BOExemplaireController::class, 'create'])->name('exemplaire.create');
    Route::post('/exemplaire/ajout', [BOExemplaireController::class, 'store'])->name('exemplaire.store');
    Route::get('/exemplaire/modification/{exemplaire}', [BOExemplaireController::class, 'edit'])->name('exemplaire.edit');
    Route::put('/exemplaire/modification/{exemplaire}', [BOExemplaireController::class, 'update'])->name('exemplaire.update');
    Route::delete('/exemplaire/suppression/{exemplaire}', [BOExemplaireController::class, 'destroy'])->name('exemplaire.destroy');

    Route::get('/usagers',                         [UsagerController::class, 'index'])->name('usagers.index');
    Route::get('/usager/{usager}',                 [UsagerController::class, 'show'])->name('usagers.show');
    Route::get('/usager/{usager}/modifier',        [UsagerController::class, 'edit'])->name('usagers.edit');
    Route::post('/usager/{usager}/modifier',       [UsagerController::class, 'update'])->name('usagers.update');
    Route::post('/usager/{usager}/suspendre',      [UsagerController::class, 'toggleSuspend'])->name('usagers.suspend');
    Route::delete('/usager/{usager}/supprimer',    [UsagerController::class, 'destroy'])->name('usagers.destroy');
});
