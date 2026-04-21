<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BiblioTEK — @yield('title', 'La Bibliothèque')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=IM+Fell+English:ital@0;1&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #1a1209;
            --bg2:       #2c1f0e;
            --bg3:       #3a2a14;
            --gold:      #c9a84c;
            --gold-light:#f0d080;
            --cream:     #f5ead8;
            --beige:     #a8916e;
            --bordeaux:  #7a2828;
            --border:    rgba(201,168,76,.18);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'IM Fell English', Georgia, serif;
            background: var(--bg);
            color: var(--cream);
            min-height: 100vh;
            /* Texture papier vieilli */
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
        }

        /* ═══════════════════════ HEADER ═══════════════════════ */
        header {
            position: sticky; top: 0; z-index: 100;
            background: rgba(26,18,9,.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
        }

        .header-inner {
            max-width: 1400px; margin: 0 auto;
            display: flex; align-items: center; gap: 2rem;
            height: 70px;
        }

        /* Logo */
        .logo {
            display: flex; align-items: center; gap: 0.6rem;
            text-decoration: none; flex-shrink: 0;
        }
        .logo-ornament { color: var(--gold); font-size: 1.4rem; line-height: 1; }
        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; font-weight: 700;
            color: var(--cream); letter-spacing: 0.02em;
        }
        .logo-text span { color: var(--gold); }

        /* Nav */
        nav { display: flex; align-items: center; gap: 0.25rem; margin-left: auto; }
        .nav-link {
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.78rem; font-weight: 600;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--beige); text-decoration: none;
            padding: 0.4rem 0.9rem; border-radius: 3px;
            transition: color .2s, background .2s;
            white-space: nowrap;
        }
        .nav-link:hover { color: var(--gold-light); background: rgba(201,168,76,.08); }
        .nav-link.active { color: var(--gold); }

        /* Search bar header */
        .header-search {
            display: flex; align-items: center;
            background: rgba(44,31,14,.8); border: 1px solid var(--border);
            border-radius: 99px; overflow: hidden;
            padding: 0 0.75rem; gap: 0.5rem;
            flex: 1; max-width: 320px;
        }
        .header-search input {
            background: none; border: none; outline: none;
            color: var(--cream); font-family: 'IM Fell English', serif;
            font-size: 0.88rem; width: 100%; padding: 0.45rem 0;
        }
        .header-search input::placeholder { color: var(--beige); opacity: .6; }
        .header-search svg { color: var(--gold); flex-shrink: 0; }

        /* Btn déco */
        .btn-nav {
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.78rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--bg); background: var(--gold);
            border: none; border-radius: 3px;
            padding: 0.4rem 1rem; cursor: pointer;
            text-decoration: none; transition: background .2s;
        }
        .btn-nav:hover { background: var(--gold-light); }

        /* ═══════════════════════ MAIN ═══════════════════════ */
        main { max-width: 1400px; margin: 0 auto; padding: 2rem 2rem 4rem; }

        /* ═══════════════════════ ALERTS ═══════════════════════ */
        .flash {
            padding: 0.8rem 1.2rem; border-radius: 4px;
            margin-bottom: 1.5rem; font-size: 0.9rem;
            font-family: 'Cormorant Garamond', serif; letter-spacing: 0.03em;
            border-left: 3px solid;
        }
        .flash-success { background: rgba(201,168,76,.1); border-color: var(--gold); color: var(--gold-light); }
        .flash-error   { background: rgba(122,40,40,.2);  border-color: var(--bordeaux); color: #e8a0a0; }
        .flash-info    { background: rgba(44,31,14,.6);   border-color: var(--beige); color: var(--beige); }

        /* ═══════════════════════ CARDS ═══════════════════════ */
        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 1.5rem;
        }

        /* ═══════════════════════ BUTTONS ═══════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.82rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            padding: 0.55rem 1.4rem; border-radius: 3px;
            border: none; cursor: pointer; text-decoration: none;
            transition: all .2s;
        }
        .btn-primary {
            background: var(--gold); color: var(--bg);
        }
        .btn-primary:hover { background: var(--gold-light); }
        .btn-secondary {
            background: transparent; color: var(--beige);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { border-color: var(--gold); color: var(--gold); }
        .btn-danger {
            background: transparent; color: #e08080;
            border: 1px solid rgba(122,40,40,.5);
        }
        .btn-danger:hover { background: rgba(122,40,40,.3); }

        /* ═══════════════════════ TABLES ═══════════════════════ */
        table { width: 100%; border-collapse: collapse; }
        th {
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.75rem; font-weight: 600;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--beige); padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(201,168,76,.06);
            font-size: 0.92rem; color: var(--cream);
            vertical-align: middle;
        }
        tr:hover td { background: rgba(201,168,76,.03); }

        /* ═══════════════════════ FORMS ═══════════════════════ */
        .field { margin-bottom: 1.2rem; }
        label {
            display: block; margin-bottom: 0.35rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.78rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--beige);
        }
        input[type=text], input[type=email], input[type=password],
        input[type=date], select, textarea {
            width: 100%; padding: 0.65rem 0.9rem;
            background: rgba(26,18,9,.8);
            border: 1px solid var(--border);
            border-radius: 4px; outline: none;
            color: var(--cream);
            font-family: 'IM Fell English', serif; font-size: 0.92rem;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,.1);
        }
        select option { background: var(--bg2); }
        .error-text {
            color: #e08080; font-size: 0.82rem; margin-top: 0.3rem;
            font-family: 'Cormorant Garamond', serif;
        }

        /* ═══════════════════════ BADGES ═══════════════════════ */
        .badge {
            display: inline-block; padding: 0.2rem 0.65rem;
            border-radius: 2px; font-size: 0.72rem;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase;
        }
        .badge-green  { background: rgba(201,168,76,.15); color: var(--gold-light); border: 1px solid rgba(201,168,76,.3); }
        .badge-red    { background: rgba(122,40,40,.3);   color: #e8a0a0; border: 1px solid rgba(122,40,40,.5); }
        .badge-yellow { background: rgba(180,120,30,.2);  color: #e8c070; border: 1px solid rgba(180,120,30,.4); }
        .badge-gray   { background: rgba(168,145,110,.1); color: var(--beige); border: 1px solid var(--border); }

        /* ═══════════════════════ HEADINGS ═══════════════════════ */
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 700;
            color: var(--cream); margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem; font-weight: 600;
            color: var(--cream); margin-bottom: 1rem;
        }

        /* ═══════════════════════ ORNEMENT SÉPARATEUR ═══════════════════════ */
        .ornament {
            text-align: center; color: var(--gold);
            font-size: 1.1rem; letter-spacing: 0.5em;
            margin: 1.5rem 0; opacity: .5;
        }

        /* ═══════════════════════ PAGINATION ═══════════════════════ */
        .pagination-wrap {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem; padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
        .pagination-info {
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.82rem; color: var(--beige);
            letter-spacing: 0.05em;
        }

        /* ═══════════════════════ FOOTER ═══════════════════════ */
        footer {
            border-top: 1px solid var(--border);
            padding: 2rem;
            text-align: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 0.8rem; color: var(--beige);
            letter-spacing: 0.1em;
            opacity: .6;
        }

        /* ═══════════════════════ UTILS ═══════════════════════ */
        .flex { display: flex; align-items: center; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .text-gold { color: var(--gold); }
        .text-beige { color: var(--beige); }
        .text-sm { font-size: 0.85rem; }
        .text-xs { font-size: 0.75rem; }
        .italic { font-style: italic; }

        /* Divider doré */
        .gold-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 1.5rem 0; opacity: .4;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--bg3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }
    </style>
    @stack('styles')
</head>
<body>

<header>
    <div class="header-inner">

        {{-- Logo --}}
        <a href="{{ route('recherche') }}" class="logo">
            <span class="logo-ornament">
                <svg width="32" height="32" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" y="0" width="200" height="200" rx="30" fill="#1a1a1a"/>
                    <rect x="0" y="0" width="200" height="200" rx="30" fill="none" stroke="#C9A84C" stroke-width="3"/>
                    <text x="30" y="158" font-size="138" font-weight="700" fill="#C9A84C" font-family="Georgia, serif">B</text>
                    <rect x="108" y="90" width="62" height="7" rx="3" fill="#e8c96a"/>
                    <rect x="108" y="107" width="48" height="7" rx="3" fill="#e8c96a"/>
                    <rect x="108" y="124" width="56" height="7" rx="3" fill="#e8c96a"/>
                </svg>
            </span>
            <span class="logo-text">Biblio<span>TEK</span></span>
        </a>

        {{-- Search --}}
        <form class="header-search" action="{{ route('recherche') }}" method="GET">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/>
            </svg>
            <input type="text" name="titre" value="{{ request('titre') }}" placeholder="Titre, auteur…">
        </form>

        {{-- Nav --}}
        <nav>
            <a href="{{ route('recherche') }}" class="nav-link {{ request()->routeIs('recherche') ? 'active' : '' }}">Catalogue</a>

            @auth
                @if(auth()->user()->estUsager())
                    <a href="{{ route('profil') }}"    class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}">Mon profil</a>
                    <a href="{{ route('emprunter') }}"  class="nav-link {{ request()->routeIs('emprunter') ? 'active' : '' }}">Emprunter</a>
                    <a href="{{ route('retour') }}"     class="nav-link {{ request()->routeIs('retour') ? 'active' : '' }}">Retour</a>
                @else
                    <a href="{{ route('bo.profils') }}"       class="nav-link {{ request()->routeIs('bo.profils*') ? 'active' : '' }}">Usagers</a>
                    <a href="{{ route('bo.usagers.index') }}" class="nav-link {{ request()->routeIs('bo.usagers*') ? 'active' : '' }}">Comptes</a>
                    <a href="{{ route('bo.exemplaires') }}"   class="nav-link {{ request()->routeIs('bo.exemplaires*') ? 'active' : '' }}">Exemplaires</a>
                @endif

                <form action="{{ route('deconnexion') }}" method="POST" style="margin-left:.5rem">
                    @csrf
                    <button type="submit" class="btn-nav">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('connexion') }}"   class="nav-link">Connexion</a>
                <a href="{{ route('inscription') }}" class="btn-nav" style="margin-left:.5rem">S'inscrire</a>
            @endauth
        </nav>

    </div>
</header>

<main>
    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="flash flash-info">{{ session('info') }}</div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="ornament">❧ ✦ ❧</div>
    BiblioTEK &ensp;·&ensp; Tous droits réservés &ensp;·&ensp; {{ date('Y') }}
</footer>

@stack('scripts')
</body>
</html>
