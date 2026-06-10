@extends('layouts.app')
@section('title', 'Catalogue')

@push('styles')
<style>
/* ── Hero ── */
.hero {
    position: relative; overflow: hidden;
    padding: 4rem 0 3rem;
    margin-bottom: 3rem;
    border-bottom: 1px solid var(--border);
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 70% 50%, rgba(201,168,76,.06) 0%, transparent 70%),
        radial-gradient(ellipse 40% 80% at 10% 50%, rgba(122,40,40,.08) 0%, transparent 60%);
    pointer-events: none;
}
.hero-eyebrow {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.75rem; font-weight: 600;
    letter-spacing: 0.3em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 1rem;
    display: flex; align-items: center; gap: 0.8rem;
}
.hero-eyebrow::before, .hero-eyebrow::after {
    content: ''; flex: none; width: 40px; height: 1px;
    background: var(--gold); opacity: .4;
}
.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 6vw, 5rem);
    font-weight: 900; line-height: 1.05;
    color: var(--cream); margin-bottom: 1.5rem;
    max-width: 700px;
}
.hero-title em { color: var(--gold); font-style: italic; }
.hero-subtitle {
    font-family: 'IM Fell English', serif;
    font-style: italic; font-size: 1.05rem;
    color: var(--beige); max-width: 480px;
    line-height: 1.7; margin-bottom: 2rem;
}
.hero-stats {
    display: flex; gap: 3rem;
}
.stat-item { text-align: center; }
.stat-number {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700;
    color: var(--gold-light);
    display: block;
}
.stat-label {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.72rem; letter-spacing: 0.15em;
    text-transform: uppercase; color: var(--beige);
}
/* Ornement hero */
.hero-ornament {
    position: absolute; right: 3rem; top: 50%;
    transform: translateY(-50%);
    opacity: .06; pointer-events: none;
    font-family: 'Playfair Display', serif;
    font-size: 18rem; line-height: 1;
    color: var(--gold); user-select: none;
    font-style: italic;
}

/* ── Layout catalogue ── */
.catalogue-wrap {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 2.5rem;
    align-items: start;
}

/* ── Sidebar ── */
.sidebar {
    position: sticky; top: 86px;
}
.sidebar-block {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 1.4rem;
    margin-bottom: 1rem;
}
.sidebar-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.7rem; font-weight: 600;
    letter-spacing: 0.2em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 1rem;
    padding-bottom: 0.6rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 0.5rem;
}
.sidebar-title svg { opacity: .7; }

/* Filtres radio */
.filter-option {
    display: flex; align-items: center;
    gap: 0.6rem; padding: 0.35rem 0;
    cursor: pointer; transition: color .15s;
}
.filter-option:hover { color: var(--gold-light); }
.filter-option input[type=radio],
.filter-option input[type=checkbox] {
    width: 14px; height: 14px; accent-color: var(--gold);
    cursor: pointer; flex-shrink: 0;
}
.filter-option label {
    font-family: 'IM Fell English', serif;
    font-size: 0.88rem; color: var(--beige);
    text-transform: none; letter-spacing: 0;
    cursor: pointer; margin: 0;
    transition: color .15s;
}
.filter-option:hover label { color: var(--gold-light); }

/* Tags étiquettes */
.tag-list { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.tag {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.72rem; font-weight: 600;
    letter-spacing: 0.07em; text-transform: uppercase;
    padding: 0.25rem 0.65rem;
    border: 1px solid var(--border);
    border-radius: 2px; color: var(--beige);
    cursor: pointer; transition: all .15s;
    text-decoration: none; display: inline-block;
}
.tag:hover, .tag.active {
    background: rgba(201,168,76,.12);
    border-color: var(--gold); color: var(--gold);
}

/* ── Grille livres ── */
.books-header {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}
.books-count {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.82rem; color: var(--beige);
    letter-spacing: 0.08em;
}
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

/* ── Book Card ── */
.book-card {
    position: relative;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    transform-style: preserve-3d;
    perspective: 800px;
}
.book-card:hover {
    transform: translateY(-6px) rotateY(-4deg);
    box-shadow: 8px 16px 40px rgba(0,0,0,.6), -2px 0 0 var(--gold);
    border-color: rgba(201,168,76,.35);
}

/* Couverture */
.book-cover {
    height: 240px;
    background: linear-gradient(135deg, var(--bg3) 0%, #1f1408 100%);
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
}
.book-cover img {
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform .4s ease;
}
.book-card:hover .book-cover img {
    transform: scale(1.04);
}
/* Dos de livre (ombre gauche) */
.book-cover::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0; width: 14px;
    background: linear-gradient(90deg, rgba(0,0,0,.6), transparent);
    z-index: 1;
}
/* Fallback lettre si pas d'image */
.book-cover-letter {
    font-family: 'Playfair Display', serif;
    font-size: 4rem; font-weight: 900; font-style: italic;
    color: rgba(201,168,76,.15);
    user-select: none; line-height: 1;
}

/* Ruban catégorie */
.book-ribbon {
    position: absolute; top: 12px; right: -24px;
    background: var(--bordeaux);
    color: #f0c0c0;
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.62rem; font-weight: 600;
    letter-spacing: 0.1em; text-transform: uppercase;
    padding: 0.2rem 2rem;
    transform: rotate(45deg);
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,.4);
}

/* Badge dispo */
.book-dispo {
    position: absolute; bottom: 8px; left: 18px;
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.68rem; font-weight: 600;
    letter-spacing: 0.08em; text-transform: uppercase;
}
.book-dispo.dispo  { color: var(--gold); }
.book-dispo.indispo{ color: #a06060; }

/* Corps card */
.book-body {
    padding: 1rem;
}
.book-title {
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem; font-weight: 700;
    color: var(--cream); line-height: 1.3;
    margin-bottom: 0.3rem;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.book-author {
    font-family: 'IM Fell English', serif;
    font-style: italic; font-size: 0.8rem;
    color: var(--beige); margin-bottom: 0.6rem;
}
.book-cats {
    display: flex; flex-wrap: wrap; gap: 0.3rem;
}
.book-cat {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.65rem; font-weight: 600;
    letter-spacing: 0.07em; text-transform: uppercase;
    color: var(--beige); opacity: .7;
}

/* ── Résultats vides ── */
.empty-state {
    text-align: center; padding: 4rem 2rem;
    color: var(--beige);
}
.empty-state .empty-icon {
    font-size: 3rem; margin-bottom: 1rem; opacity: .3;
}
.empty-state p {
    font-family: 'IM Fell English', serif;
    font-style: italic; font-size: 1.05rem;
}

/* ── Responsive ── */
@media (max-width: 900px) {
    .catalogue-wrap { grid-template-columns: 1fr; }
    .sidebar { position: static; }
    .hero-ornament { display: none; }
    .hero-stats { gap: 1.5rem; }
}
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
@if(!request()->hasAny(['titre','auteur_id','categorie_id','disponible']))
<section class="hero">
    <div class="hero-ornament">B</div>

    <div class="hero-eyebrow">Collection</div>

    <h1 class="hero-title">
        Une bibliothèque<br>
        <em>pour les âmes</em><br>
        curieuses.
    </h1>

    <p class="hero-subtitle">
        Des milliers d'ouvrages soigneusement catalogués,
        vous attendent dans nos rayonnages.
        Parcourez, découvrez, empruntez.
    </p>

    <div class="hero-stats">
        <div class="stat-item">
            <span class="stat-number">{{ \App\Models\Livre::count() }}</span>
            <span class="stat-label">Ouvrages</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ \App\Models\Exemplaire::whereHas('statut', fn($q) => $q->where('statut','disponible'))->count() }}</span>
            <span class="stat-label">Disponibles</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ \App\Models\Auteur::count() }}</span>
            <span class="stat-label">Auteurs</span>
        </div>
    </div>
</section>
@endif

{{-- ═══ CATALOGUE ═══ --}}
<div class="catalogue-wrap">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <form action="{{ route('recherche') }}" method="GET" id="filter-form">

            {{-- Recherche --}}
            <div class="sidebar-block">
                <div class="sidebar-title">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>
                    Recherche
                </div>
                <div class="field" style="margin:0">
                    <input type="text" name="titre" value="{{ request('titre') }}" placeholder="Titre du livre…">
                </div>
            </div>

            {{-- Auteur --}}
            <div class="sidebar-block">
                <div class="sidebar-title">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Auteur
                </div>
                <div class="field" style="margin:0">
                    <select name="auteur_id">
                        <option value="">— Tous les auteurs —</option>
                        @foreach($auteurs as $auteur)
                            <option value="{{ $auteur->id }}" @selected(request('auteur_id') == $auteur->id)>
                                {{ $auteur->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Catégories --}}
            <div class="sidebar-block">
                <div class="sidebar-title">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    Catégories
                </div>
                <div class="tag-list">
                    @foreach($categories as $cat)
                        <a href="{{ route('recherche', array_merge(request()->query(), ['categorie_id' => $cat->id])) }}"
                           class="tag {{ request('categorie_id') == $cat->id ? 'active' : '' }}">
                            {{ $cat->categorie }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Disponibilité --}}
            <div class="sidebar-block">
                <div class="sidebar-title">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Disponibilité
                </div>
                <label class="filter-option">
                    <input type="checkbox" name="disponible" value="1" @checked(request('disponible'))
                           onchange="document.getElementById('filter-form').submit()">
                    <label>Disponibles uniquement</label>
                </label>
            </div>

            <div style="display:flex;gap:0.5rem">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">Filtrer</button>
                <a href="{{ route('recherche') }}" class="btn btn-secondary">✕</a>
            </div>

        </form>
    </aside>

    {{-- ── Grille ── --}}
    <section>
        <div class="books-header">
            <span class="books-count">
                @if($livres->total())
                    <strong style="color:var(--gold-light)">{{ $livres->total() }}</strong>
                    ouvrage{{ $livres->total() > 1 ? 's' : '' }} trouvé{{ $livres->total() > 1 ? 's' : '' }}
                @endif
            </span>
            @if(request()->hasAny(['titre','auteur_id','categorie_id','disponible']))
                <a href="{{ route('recherche') }}" class="btn btn-secondary text-xs">Réinitialiser les filtres</a>
            @endif
        </div>

        @if($livres->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📚</div>
                <p>Aucun ouvrage ne correspond à votre recherche.</p>
            </div>
        @else
        <div class="books-grid">
            @foreach($livres as $livre)
                @php
                    $nbDispo = $livre->exemplaires->filter(fn($e) => $e->statut->statut === 'disponible')->count();
                    $nbTotal = $livre->exemplaires->count();
                    $firstCat = $livre->categories->first()?->categorie;
                    $letter = mb_strtoupper(mb_substr($livre->titre, 0, 1));
                @endphp
                <article class="book-card">

                    {{-- Couverture --}}
                    <div class="book-cover">
                        @if($livre->cover_url)
                            <img
                                src="{{ $livre->cover_url }}"
                                alt="Couverture de {{ $livre->titre }}"
                                loading="lazy"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='block'"
                            >
                            <span class="book-cover-letter" style="display:none">{{ $letter }}</span>
                        @else
                            <span class="book-cover-letter">{{ $letter }}</span>
                        @endif

                        @if($firstCat)
                            <div class="book-ribbon">{{ $firstCat }}</div>
                        @endif

                        <div class="book-dispo {{ $nbDispo > 0 ? 'dispo' : 'indispo' }}">
                            @if($nbDispo > 0)
                                ◆ {{ $nbDispo }}/{{ $nbTotal }} dispo.
                            @else
                                ◇ Indisponible
                            @endif
                        </div>
                    </div>

                    {{-- Corps --}}
                    <div class="book-body">
                        <div class="book-title">{{ $livre->titre }}</div>
                        <div class="book-author">{{ $livre->auteur->nom }}</div>
                        @if($livre->categories->isNotEmpty())
                            <div class="book-cats">
                                @foreach($livre->categories->take(2) as $cat)
                                    <span class="book-cat">{{ $cat->categorie }}</span>
                                    @if(!$loop->last) <span class="book-cat" style="opacity:.3">·</span> @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="margin-top:2rem">{{ $livres->links() }}</div>
        @endif

    </section>

</div>
@endsection
