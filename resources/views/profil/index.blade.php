@extends('layouts.app')
@section('title', 'Mon profil')

@push('styles')
<style>
.profil-header {
    display: flex; align-items: center; gap: 1.5rem;
    padding: 2rem; background: var(--bg2);
    border: 1px solid var(--border); border-radius: 6px;
    margin-bottom: 2rem;
}
.profil-avatar {
    width: 64px; height: 64px; border-radius: 50%;
    background: linear-gradient(135deg, var(--bg3), var(--bg));
    border: 2px solid var(--gold);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 700; color: var(--gold);
}
.profil-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem; font-weight: 700; color: var(--cream);
}
.profil-email {
    font-family: 'IM Fell English', serif;
    font-style: italic; font-size: 0.88rem; color: var(--beige);
    margin-top: 0.2rem;
}
.profil-role {
    margin-left: auto; text-align: right;
}

.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; font-weight: 600;
    color: var(--cream); margin-bottom: 1.2rem;
    display: flex; align-items: center; gap: 0.75rem;
}
.section-title::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(90deg, rgba(201,168,76,.3), transparent);
}

.emprunt-actif {
    background: var(--bg2);
    border: 1px solid rgba(201,168,76,.25);
    border-left: 3px solid var(--gold);
    border-radius: 4px; padding: 1.5rem;
    margin-bottom: 2rem;
}
.emprunt-meta {
    display: flex; align-items: center; gap: 1.5rem;
    margin-bottom: 1rem; flex-wrap: wrap;
}
.emprunt-meta-item {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.78rem; letter-spacing: 0.1em;
    text-transform: uppercase; color: var(--beige);
}
.emprunt-meta-item strong {
    display: block; color: var(--cream);
    font-family: 'IM Fell English', serif;
    font-size: 0.95rem; text-transform: none;
    letter-spacing: 0; margin-top: 0.1rem;
}
.retard-warn {
    background: rgba(122,40,40,.2); border: 1px solid rgba(122,40,40,.4);
    border-radius: 3px; padding: 0.5rem 1rem;
    color: #e8a0a0;
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.82rem; letter-spacing: 0.05em;
    margin-bottom: 1rem;
    display: flex; align-items: center; gap: 0.5rem;
}
</style>
@endpush

@section('content')

{{-- En-tête profil --}}
<div class="profil-header">
    <div class="profil-avatar">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
    <div>
        <div class="profil-name">{{ $user->name }}</div>
        <div class="profil-email">{{ $user->email }}</div>
    </div>
    <div class="profil-role">
        <span class="badge badge-gray">Usager</span>
        <div style="font-family:'Cormorant Garamond',serif;font-size:0.75rem;color:var(--beige);margin-top:.4rem;letter-spacing:.05em">
            Membre depuis {{ $user->created_at->format('Y') }}
        </div>
    </div>
</div>

{{-- Emprunt actif --}}
<div class="section-title">Emprunt en cours</div>

@if($empruntActif)
    <div class="emprunt-actif">

        @if($empruntActif->estEnRetard())
            <div class="retard-warn">
                ⚠ Retour en retard — veuillez rapporter vos livres dès que possible.
            </div>
        @endif

        <div class="emprunt-meta">
            <div class="emprunt-meta-item">
                Date d'emprunt
                <strong>{{ $empruntActif->date_emprunt->format('d/m/Y') }}</strong>
            </div>
            <div class="emprunt-meta-item">
                À rendre avant le
                <strong style="{{ $empruntActif->estEnRetard() ? 'color:#e8a0a0' : 'color:var(--gold-light)' }}">
                    {{ $empruntActif->date_retour_prevue->format('d/m/Y') }}
                </strong>
            </div>
            <div class="emprunt-meta-item">
                Exemplaires
                <strong>{{ $empruntActif->exemplaires->count() }} livre(s)</strong>
            </div>
        </div>

        <table>
            <thead>
                <tr><th>Titre</th><th>Auteur</th></tr>
            </thead>
            <tbody>
            @foreach($empruntActif->exemplaires as $exemplaire)
                <tr>
                    <td>{{ $exemplaire->livre->titre }}</td>
                    <td class="text-beige italic text-sm">{{ $exemplaire->livre->auteur->nom }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form action="{{ route('retour.store', $empruntActif) }}" method="POST" style="margin-top:1.2rem">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 109 9" stroke-linecap="round"/><path d="M3 12V6M3 12H9" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Signaler le retour
            </button>
            <a href="{{ route('emprunt.show', $empruntActif) }}" class="btn btn-secondary" style="margin-left:.5rem">Détail</a>
        </form>
    </div>

@else
    <div class="card mb-3" style="text-align:center;padding:2rem">
        <p class="text-beige italic" style="font-family:'IM Fell English',serif;margin-bottom:1rem">
            Vous n'avez aucun emprunt en cours.
        </p>
        <a href="{{ route('emprunter') }}" class="btn btn-primary">Parcourir le catalogue →</a>
    </div>
@endif

{{-- Historique --}}
@if($historique->count())
    <div class="section-title" style="margin-top:2rem">Historique</div>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Date emprunt</th>
                    <th>Date retour</th>
                    <th>Livres</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($historique as $emprunt)
                <tr>
                    <td class="text-sm">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                    <td class="text-sm text-beige">{{ $emprunt->date_retour_effective?->format('d/m/Y') ?? '—' }}</td>
                    <td class="text-sm">{{ $emprunt->exemplaires->count() }} exemplaire(s)</td>
                    <td>
                        <a href="{{ route('emprunt.show', $emprunt) }}" class="btn btn-secondary text-xs">Voir</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
