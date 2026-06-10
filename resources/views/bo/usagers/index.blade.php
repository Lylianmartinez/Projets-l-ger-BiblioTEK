@extends('layouts.app')
@section('title', 'Gestion des usagers')

@section('content')

<div class="flex-between flex-wrap" style="margin-bottom:1.25rem">
    <h1 style="margin:0">Gestion des usagers</h1>
</div>

<div class="card mb-2">
    <form action="{{ route('bo.usagers.index') }}" method="GET" style="display:flex;gap:.75rem;align-items:flex-end">
        <div class="field" style="margin:0;flex:1">
            <label>Rechercher par nom ou email</label>
            <input type="text" name="q" value="{{ $search }}" placeholder="Nom ou email…">
        </div>
        <button type="submit" class="btn btn-primary">Rechercher</button>
        @if($search)
            <a href="{{ route('bo.usagers.index') }}" class="btn btn-secondary">Réinitialiser</a>
        @endif
    </form>
</div>

<div class="card">
    @if($usagers->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">👤</div>
            <p>Aucun usager trouvé.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Inscription</th>
                        <th>Emprunts en cours</th>
                        <th>Statut</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usagers as $usager)
                    <tr>
                        <td style="font-weight:500">{{ $usager->name }}</td>
                        <td class="text-muted">{{ $usager->email }}</td>
                        <td class="text-muted">{{ $usager->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($usager->emprunts_en_cours_count > 0)
                                <span class="badge badge-yellow">{{ $usager->emprunts_en_cours_count }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($usager->is_active)
                                <span class="badge badge-green">Actif</span>
                            @else
                                <span class="badge badge-red">Suspendu</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex" style="justify-content:flex-end;gap:.4rem">
                                <a href="{{ route('bo.usagers.show', $usager) }}" class="btn btn-secondary btn-sm">Voir</a>
                                <a href="{{ route('bo.usagers.edit', $usager) }}" class="btn btn-secondary btn-sm">Modifier</a>

                                <form action="{{ route('bo.usagers.suspend', $usager) }}" method="POST" style="margin:0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $usager->is_active ? 'btn-danger' : 'btn-primary' }}">
                                        {{ $usager->is_active ? 'Suspendre' : 'Réactiver' }}
                                    </button>
                                </form>

                                <form action="{{ route('bo.usagers.destroy', $usager) }}" method="POST" style="margin:0"
                                      onsubmit="return confirm('Supprimer définitivement le compte de {{ addslashes($usager->name) }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($usagers->hasPages())
            <div style="margin-top:1.25rem">{{ $usagers->links() }}</div>
        @endif
    @endif
</div>

@endsection
