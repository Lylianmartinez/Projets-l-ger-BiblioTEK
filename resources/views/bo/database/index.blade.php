@extends('layouts.app')
@section('title', 'Base de données – Back-office')

@push('styles')
<style>
    .db-grid { display: grid; grid-template-columns: 260px 1fr; gap: 1.5rem; align-items: start; }
    .db-sidebar .card { padding: 0.75rem; }
    .db-tables { list-style: none; }
    .db-tables li a {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.45rem 0.7rem; border-radius: 4px; text-decoration: none;
        color: var(--cream); font-size: 0.9rem; transition: background .15s;
    }
    .db-tables li a:hover { background: rgba(201,168,76,.08); }
    .db-tables li a.active { background: rgba(201,168,76,.15); color: var(--gold-light); }
    .db-count { font-family: 'Cormorant Garamond', serif; font-size: 0.72rem; color: var(--beige); }
    .sql-console textarea {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', monospace;
        font-size: 0.85rem; min-height: 110px; resize: vertical; line-height: 1.5;
    }
    .table-scroll { overflow-x: auto; }
    .table-scroll table { min-width: 100%; }
    .table-scroll td { font-family: 'SFMono-Regular', Consolas, monospace; font-size: 0.82rem; white-space: nowrap; max-width: 380px; overflow: hidden; text-overflow: ellipsis; }
    .sql-error {
        font-family: 'SFMono-Regular', Consolas, monospace; font-size: 0.82rem;
        white-space: pre-wrap; color: #e8a0a0;
    }
    .null-cell { color: var(--beige); opacity: .5; font-style: italic; }
</style>
@endpush

@section('content')
<div class="flex mb-2" style="justify-content:space-between">
    <h1 style="margin:0">Base de données</h1>
    <span class="badge badge-gray">SQLite · {{ count($tables) }} tables</span>
</div>

<div class="db-grid">

    {{-- ─── Barre latérale : liste des tables ─────────────────────────── --}}
    <aside class="db-sidebar">
        <div class="card">
            <ul class="db-tables">
                @foreach($tables as $t)
                    <li>
                        <a href="{{ route('bo.database', ['table' => $t]) }}"
                           class="{{ ($table ?? null) === $t ? 'active' : '' }}">
                            <span>{{ $t }}</span>
                            <span class="db-count">{{ $counts[$t] ?? '?' }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>

    <div>
        {{-- ─── Console SQL ───────────────────────────────────────────── --}}
        <div class="card sql-console mb-3">
            <h2>Console SQL</h2>
            <form action="{{ route('bo.database.query') }}" method="POST">
                @csrf
                <div class="field" style="margin-bottom:.8rem">
                    <textarea name="sql" placeholder="SELECT * FROM livres LIMIT 20;">{{ $sql ?? '' }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Exécuter</button>
                <span class="text-beige text-xs italic" style="margin-left:.8rem">
                    SELECT/PRAGMA → résultats · INSERT/UPDATE/DELETE/… → lignes affectées
                </span>
            </form>

            @isset($error)
                @if($error !== null)
                    <div class="gold-line"></div>
                    <div class="sql-error">⚠ {{ $error }}</div>
                @endif
            @endisset

            @isset($affected)
                @if($affected !== null)
                    <div class="gold-line"></div>
                    <div class="flash flash-success" style="margin:0">{{ $affected }} ligne(s) affectée(s).</div>
                @endif
            @endisset

            @isset($result)
                @if($result !== null)
                    <div class="gold-line"></div>
                    <div class="text-beige text-sm mb-1">{{ count($result) }} ligne(s)</div>
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>@foreach($resultCols as $c)<th>{{ $c }}</th>@endforeach</tr>
                            </thead>
                            <tbody>
                            @forelse($result as $row)
                                <tr>
                                    @foreach($resultCols as $c)
                                        <td>{!! $row[$c] === null ? '<span class="null-cell">NULL</span>' : e($row[$c]) !!}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="{{ max(count($resultCols), 1) }}" class="text-beige italic">Aucun résultat.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            @endisset
        </div>

        {{-- ─── Parcours de table ─────────────────────────────────────── --}}
        @if(($table ?? null) !== null)
            <div class="card">
                <div class="flex mb-2" style="justify-content:space-between">
                    <h2 style="margin:0">Table <span class="text-gold">{{ $table }}</span></h2>
                    <span class="badge badge-gray">{{ $rows->total() }} ligne(s)</span>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>@foreach($columns as $c)<th>{{ $c }}</th>@endforeach</tr>
                        </thead>
                        <tbody>
                        @forelse($rows as $row)
                            <tr>
                                @foreach($columns as $c)
                                    @php $val = $row->{$c} ?? null; @endphp
                                    <td>{!! $val === null ? '<span class="null-cell">NULL</span>' : e($val) !!}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr><td colspan="{{ max(count($columns), 1) }}" class="text-beige italic">Table vide.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:1rem">{{ $rows->links() }}</div>
            </div>
        @else
            <div class="card text-beige italic">
                Sélectionnez une table à gauche pour la parcourir, ou lancez une requête SQL ci-dessus.
            </div>
        @endif
    </div>
</div>
@endsection
