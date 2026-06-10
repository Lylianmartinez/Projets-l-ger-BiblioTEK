@extends('layouts.app')
@section('title', 'Connexion')

@push('styles')
<style>
.auth-wrap {
    min-height: 70vh;
    display: flex; align-items: center; justify-content: center;
    padding: 2rem;
}
.auth-card {
    width: 100%; max-width: 420px;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 2.5rem;
}
.auth-logo {
    text-align: center; margin-bottom: 2rem;
}
.auth-logo-icon {
    color: var(--gold); font-size: 2rem; display: block; margin-bottom: 0.5rem;
}
.auth-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem; font-weight: 700;
    color: var(--cream); text-align: center;
    margin-bottom: 0.3rem;
}
.auth-subtitle {
    font-family: 'IM Fell English', serif;
    font-style: italic; font-size: 0.88rem;
    color: var(--beige); text-align: center;
    margin-bottom: 2rem;
}
.auth-footer {
    text-align: center; margin-top: 1.5rem;
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.82rem; color: var(--beige);
    letter-spacing: 0.03em;
}
.auth-footer a { color: var(--gold); text-decoration: none; }
.auth-footer a:hover { color: var(--gold-light); }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">

        <div class="auth-logo">
            <span class="auth-logo-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    <path d="M8 7h8M8 11h6" stroke-linecap="round"/>
                </svg>
            </span>
        </div>

        <h1 class="auth-title">Connexion</h1>
        <p class="auth-subtitle">Accédez à votre espace bibliothèque</p>

        <div class="gold-line"></div>

        <form action="{{ url('/connexion') }}" method="POST">
            @csrf

            <div class="field">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="votre@email.fr">
                @error('email') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" autocomplete="current-password" placeholder="••••••••">
                @error('password') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div class="field" style="display:flex;align-items:center;gap:0.5rem">
                <input type="checkbox" name="remember" id="remember" style="width:auto;accent-color:var(--gold)">
                <label for="remember" style="text-transform:none;letter-spacing:0;cursor:pointer;margin:0;font-size:0.85rem">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem;padding:.7rem">
                Se connecter
            </button>
        </form>

        <p class="auth-footer">
            Pas encore de compte ?
            <a href="{{ route('inscription') }}">S'inscrire</a>
        </p>
    </div>
</div>
@endsection
