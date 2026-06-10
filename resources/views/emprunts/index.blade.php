@extends('layouts.app')
@section('title', 'Emprunter')

@section('content')
<h1>Emprunter des exemplaires</h1>

@error('emprunt')
    <div class="alert alert-error">{{ $message }}</div>
@enderror

{{-- Barre de recherche --}}
<div class="card mb-2">
    <form action="{{ route('emprunter') }}" method="GET">
        <div style="display:flex;gap:0.75rem;align-items:center">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Rechercher par titre ou auteur..."
                style="flex:1"
                autofocus
            >
            <button type="submit" class="btn btn-primary" style="white-space:nowrap">Rechercher</button>
            @if(request('q'))
                <a href="{{ route('emprunter') }}" class="btn btn-secondary" style="white-space:nowrap">Effacer</a>
            @endif
        </div>
    </form>
</div>

@if(request('q'))
    <p class="text-muted text-sm mb-2">
        Résultats pour <strong>« {{ request('q') }} »</strong> —
        {{ $exemplairesDisponibles->total() }} exemplaire(s) disponible(s)
    </p>
@endif

@if($exemplairesDisponibles->isEmpty())
    <div class="card">
        <p>Aucun exemplaire disponible{{ request('q') ? ' pour cette recherche' : '' }}.</p>
    </div>
@else
<div class="card">
    <p class="text-muted text-sm mb-2">Sélectionnez un ou plusieurs exemplaires à emprunter (durée : 30 jours).</p>
    <form action="{{ route('emprunter.store') }}" method="POST">
        @csrf
        <table>
            <thead>
                <tr>
                    <th style="width:40px"></th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Mis en service</th>
                </tr>
            </thead>
            <tbody>
            @foreach($exemplairesDisponibles as $exemplaire)
                <tr>
                    <td>
                        <input type="checkbox" name="exemplaires[]" value="{{ $exemplaire->id }}" style="width:auto">
                    </td>
                    <td>{{ $exemplaire->livre->titre }}</td>
                    <td>{{ $exemplaire->livre->auteur->nom }}</td>
                    <td class="text-muted text-sm">{{ $exemplaire->mise_en_service->format('d/m/Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @error('exemplaires') <p class="error-text" style="margin-top:0.5rem">{{ $message }}</p> @enderror
        @error('exemplaires.*') <p class="error-text" style="margin-top:0.5rem">{{ $message }}</p> @enderror

        <button type="submit" class="btn btn-primary" style="margin-top:1rem">Confirmer l'emprunt</button>
    </form>

    <div style="margin-top:1rem">{{ $exemplairesDisponibles->links() }}</div>
</div>
@endif
@endsection
