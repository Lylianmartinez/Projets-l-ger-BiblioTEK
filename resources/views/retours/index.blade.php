@extends('layouts.app')
@section('title', 'Retour')

@section('content')
<h1>Retourner mes exemplaires</h1>

@if(!$empruntActif)
    <div class="card">
        <p>Vous n'avez aucun emprunt en cours.</p>
        <a href="{{ route('emprunter') }}" class="btn btn-primary" style="margin-top:0.75rem">Emprunter des livres</a>
    </div>
@else
<div class="card">
    <p class="text-muted text-sm mb-2">
        Date de retour prévue :
        <strong class="{{ $empruntActif->estEnRetard() ? 'badge badge-red' : '' }}">
            {{ $empruntActif->date_retour_prevue->format('d/m/Y') }}
        </strong>
    </p>

    <table>
        <thead><tr><th>Titre</th><th>Auteur</th></tr></thead>
        <tbody>
        @foreach($empruntActif->exemplaires as $exemplaire)
            <tr>
                <td>{{ $exemplaire->livre->titre }}</td>
                <td>{{ $exemplaire->livre->auteur->nom }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <form action="{{ route('retour.store', $empruntActif) }}" method="POST" style="margin-top:1rem">
        @csrf
        <p class="text-sm text-muted mb-1">En confirmant, vous signalez avoir déposé les livres. Le bibliothécaire validera le retour.</p>
        <button type="submit" class="btn btn-primary">Confirmer le dépôt</button>
    </form>
</div>
@endif
@endsection
