@extends('layouts.app')
@section('title', 'Fiche usager – ' . $usager->name)

@section('content')

<div class="flex-between flex-wrap" style="margin-bottom:1.25rem">
    <div class="flex" style="gap:.75rem">
        <a href="{{ route('bo.usagers.index') }}" class="btn btn-secondary btn-sm">← Retour</a>
        <h1 style="margin:0">{{ $usager->name }}</h1>
        @if($usager->is_active)
            <span class="badge badge-green">Actif</span>
        @else
            <span class="badge badge-red">Suspendu</span>
        @endif
    </div>
    <div class="flex" style="gap:.5rem">
        <a href="{{ route('bo.usagers.edit', $usager) }}" class="btn btn-primary btn-sm">Modifier</a>

        <form action="{{ route('bo.usagers.suspend', $usager) }}" method="POST" style="margin:0">
            @csrf
            <button type="submit" class="btn btn-sm {{ $usager->is_active ? 'btn-danger' : 'btn-primary' }}">
                {{ $usager->is_active ? 'Suspendre' : 'Réactiver' }}
            </button>
        </form>
    </div>
</div>

<div class="card mb-2">
    <div class="card-title">Informations du compte</div>
    <div class="info-row">
        <span class="info-label">Nom</span>
        <span>{{ $usager->name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Email</span>
        <span>{{ $usager->email }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Rôle</span>
        <span>{{ ucfirst($usager->role) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Inscrit le</span>
        <span>{{ $usager->created_at->format('d/m/Y à H:i') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Statut</span>
        @if($usager->is_active)
            <span class="badge badge-green">Actif</span>
        @else
            <span class="badge badge-red">Suspendu</span>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-title">Historique des emprunts ({{ $usager->emprunts->count() }})</div>

    @if($usager->emprunts->isEmpty())
        <div class="empty-state" style="padding:1.5rem">
            <p style="margin:0">Aucun emprunt enregistré.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Livre(s)</th>
                        <th>Emprunté le</th>
                        <th>Retour prévu</th>
                        <th>Retour effectif</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usager->emprunts as $emprunt)
                    <tr>
                        <td>
                            @foreach($emprunt->exemplaires as $exemplaire)
                                <div>{{ $exemplaire->livre->titre }}</div>
                            @endforeach
                        </td>
                        <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                        <td>{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                        <td>
                            {{ $emprunt->date_retour_effective
                                ? $emprunt->date_retour_effective->format('d/m/Y')
                                : '—' }}
                        </td>
                        <td>
                            @if($emprunt->estRendu())
                                <span class="badge badge-green">Rendu</span>
                            @elseif($emprunt->estEnRetard())
                                <span class="badge badge-red">En retard</span>
                            @else
                                <span class="badge badge-yellow">En cours</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
