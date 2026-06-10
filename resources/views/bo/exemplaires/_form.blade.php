<div class="field">
    <label for="livre_id">Livre</label>
    <select name="livre_id" id="livre_id">
        <option value="">— Choisir un livre —</option>
        @foreach($livres as $livre)
            <option value="{{ $livre->id }}" @selected(old('livre_id', $exemplaire->livre_id ?? '') == $livre->id)>
                {{ $livre->titre }} ({{ $livre->auteur->nom }})
            </option>
        @endforeach
    </select>
    @error('livre_id') <p class="error-text">{{ $message }}</p> @enderror
</div>

<div class="field">
    <label for="statut_id">Statut</label>
    <select name="statut_id" id="statut_id">
        @foreach($statuts as $statut)
            <option value="{{ $statut->id }}" @selected(old('statut_id', $exemplaire->statut_id ?? '') == $statut->id)>
                {{ ucfirst($statut->statut) }}
            </option>
        @endforeach
    </select>
    @error('statut_id') <p class="error-text">{{ $message }}</p> @enderror
</div>

<div class="field">
    <label for="mise_en_service">Date de mise en service</label>
    <input type="date" name="mise_en_service" id="mise_en_service"
           value="{{ old('mise_en_service', isset($exemplaire) ? $exemplaire->mise_en_service->format('Y-m-d') : '') }}">
    @error('mise_en_service') <p class="error-text">{{ $message }}</p> @enderror
</div>
