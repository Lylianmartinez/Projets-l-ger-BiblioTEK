@extends('layouts.app')
@section('title', 'Exemplaires – Back-office')

@section('content')
<div class="flex mb-2">
    <h1 style="margin:0">Gestion des exemplaires</h1>
    <a href="{{ route('bo.exemplaire.create') }}" class="btn btn-primary">+ Ajouter</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr><th>ID</th><th>Titre</th><th>Auteur</th><th>Statut</th><th>Mise en service</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @foreach($exemplaires as $exemplaire)
            <tr>
                <td class="text-muted text-sm">#{{ $exemplaire->id }}</td>
                <td>{{ $exemplaire->livre->titre }}</td>
                <td>{{ $exemplaire->livre->auteur->nom }}</td>
                <td>
                    @php $s = $exemplaire->statut->statut; @endphp
                    <span class="badge {{ $s === 'disponible' ? 'badge-green' : ($s === 'emprunté' ? 'badge-yellow' : 'badge-red') }}">
                        {{ ucfirst($s) }}
                    </span>
                </td>
                <td class="text-sm">{{ $exemplaire->mise_en_service->format('d/m/Y') }}</td>
                <td class="flex">
                    <a href="{{ route('bo.exemplaire.edit', $exemplaire) }}" class="btn btn-secondary text-sm">Modifier</a>
                    <form action="{{ route('bo.exemplaire.destroy', $exemplaire) }}" method="POST"
                          onsubmit="return confirm('Supprimer cet exemplaire ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger text-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:1rem">{{ $exemplaires->links() }}</div>
</div>
@endsection
