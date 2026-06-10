@extends('layouts.app')
@section('title', 'Usagers – Back-office')

@section('content')
<h1>Gestion des usagers</h1>

<div class="card">
    <table>
        <thead>
            <tr><th>Nom</th><th>Email</th><th>Emprunts</th><th></th></tr>
        </thead>
        <tbody>
        @foreach($usagers as $usager)
            <tr>
                <td>{{ $usager->name }}</td>
                <td>{{ $usager->email }}</td>
                <td>{{ $usager->emprunts_count }}</td>
                <td><a href="{{ route('bo.profil.show', $usager) }}" class="btn btn-secondary text-sm">Voir</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:1rem">{{ $usagers->links() }}</div>
</div>
@endsection
