@extends('layouts.app')
@section('title', 'Ajouter un exemplaire')

@section('content')
<a href="{{ route('bo.exemplaires') }}" class="btn btn-secondary mb-2">← Retour</a>
<h1>Ajouter un exemplaire</h1>

<div class="card" style="max-width:500px">
    <form action="{{ route('bo.exemplaire.store') }}" method="POST">
        @csrf
        @include('bo.exemplaires._form')
        <button type="submit" class="btn btn-primary" style="margin-top:0.75rem">Ajouter</button>
    </form>
</div>
@endsection
