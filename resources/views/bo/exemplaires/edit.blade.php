@extends('layouts.app')
@section('title', 'Modifier un exemplaire')

@section('content')
<a href="{{ route('bo.exemplaires') }}" class="btn btn-secondary mb-2">← Retour</a>
<h1>Modifier l'exemplaire #{{ $exemplaire->id }}</h1>

<div class="card" style="max-width:500px">
    <form action="{{ route('bo.exemplaire.update', $exemplaire) }}" method="POST">
        @csrf @method('PUT')
        @include('bo.exemplaires._form')
        <button type="submit" class="btn btn-primary" style="margin-top:0.75rem">Enregistrer</button>
    </form>
</div>
@endsection
