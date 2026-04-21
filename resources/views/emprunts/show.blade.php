@extends('layouts.app')
@section('title', 'Détail emprunt')

@section('content')
<h1>Détail de l'emprunt #{{ $emprunt->id }}</h1>

<div class="card mb-2">
    <p><strong>Usager :</strong> {{ $emprunt->user->name }}</p>
    <p><strong>Date d'emprunt :</strong> {{ $emprunt->date_emprunt->format('d/m/Y') }}</p>
    <p><strong>Date de retour prévue :</strong> {{ $emprunt->date_retour_prevue->format('d/m/Y') }}</p>
    <p>
        <strong>Statut :</strong>
        @if($emprunt->estRendu())
            <span class="badge badge-green">Rendu le {{ $emprunt->date_retour_effective->format('d/m/Y') }}</span>
        @elseif($emprunt->estEnRetard())
            <span class="badge badge-red">En retard</span>
        @else
            <span class="badge badge-yellow">En cours</span>
        @endif
    </p>
</div>

<div class="card">
    <h2>Exemplaires empruntés</h2>
    <table>
        <thead><tr><th>Titre</th><th>Auteur</th></tr></thead>
        <tbody>
        @foreach($emprunt->exemplaires as $exemplaire)
            <tr>
                <td>{{ $exemplaire->livre->titre }}</td>
                <td>{{ $exemplaire->livre->auteur->nom }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<a href="{{ route('profil') }}" class="btn btn-secondary" style="margin-top:1rem">← Retour au profil</a>
@endsection
