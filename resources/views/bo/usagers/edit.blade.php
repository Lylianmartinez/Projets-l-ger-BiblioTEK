@extends('layouts.app')
@section('title', 'Modifier – ' . $usager->name)

@section('content')

<div class="flex" style="gap:.75rem;margin-bottom:1.25rem">
    <a href="{{ route('bo.usagers.show', $usager) }}" class="btn btn-secondary btn-sm">← Retour</a>
    <h1 style="margin:0">Modifier le compte</h1>
</div>

<div class="card" style="max-width:520px">
    <form action="{{ route('bo.usagers.update', $usager) }}" method="POST">
        @csrf

        <div class="field">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $usager->name) }}" required>
            @error('name') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $usager->email) }}" required>
            @error('email') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <option value="usager"         @selected(old('role', $usager->role) === 'usager')>Usager</option>
                <option value="bibliothecaire" @selected(old('role', $usager->role) === 'bibliothecaire')>Bibliothécaire</option>
            </select>
            @error('role') <span class="error-text">{{ $message }}</span> @enderror
        </div>

        <div class="flex" style="gap:.5rem;margin-top:1.5rem">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('bo.usagers.show', $usager) }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

@endsection
