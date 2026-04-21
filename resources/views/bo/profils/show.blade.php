@extends('layouts.app')
@section('title', 'Profil usager')

@section('content')
<a href="{{ route('bo.profils') }}" class="btn btn-secondary mb-2">← Retour</a>
<h1>{{ $user->name }}</h1>

<div class="card mb-2">
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Inscrit le :</strong> {{ $user->created_at->format('d/m/Y') }}</p>
</div>

<div class="card">
    <h2>Emprunts</h2>
    @if($user->emprunts->isEmpty())
        <p class="text-muted">Aucun emprunt.</p>
    @else
    <table>
        <thead>
            <tr><th>Date emprunt</th><th>Date retour prévue</th><th>Statut</th><th>Exemplaires</th><th>Action</th></tr>
        </thead>
        <tbody>
        @foreach($user->emprunts as $emprunt)
            <tr>
                <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                <td>{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                <td>
                    @if($emprunt->estRendu())
                        <span class="badge badge-green">Rendu</span>
                    @elseif($emprunt->estEnRetard())
                        <span class="badge badge-red">En retard</span>
                    @else
                        <span class="badge badge-yellow">En cours</span>
                    @endif
                </td>
                <td class="text-sm">{{ $emprunt->exemplaires->pluck('livre.titre')->implode(', ') }}</td>
                <td>
                    @if(!$emprunt->estRendu())
                        <form action="{{ route('bo.retour.valider', $emprunt) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary text-sm">Valider retour</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
