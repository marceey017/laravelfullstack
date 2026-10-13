@if ($manufacturers->isEmpty())
    <div class="alert alert-warning" role="alert">
        Előbb hozz létre egy gyártót.
        <a class="alert-link" href="{{ route('manufacturers.create') }}">Új gyártó hozzáadása</a>
    </div>
@endif
<div class="mb-3">
    <label class="form-label" for="manufacturer_id">Gyártó</label>
    <select class="form-select @error('manufacturer_id') is-invalid @enderror" id="manufacturer_id" name="manufacturer_id" required @error('manufacturer_id') aria-invalid="true" aria-describedby="manufacturer-error" @enderror>
        <option value="">Válassz gyártót</option>
        @foreach ($manufacturers as $manufacturer)
            <option value="{{ $manufacturer->id }}" @selected((string) old('manufacturer_id', $carModel?->manufacturer_id) === (string) $manufacturer->id)>{{ $manufacturer->name }}</option>
        @endforeach
    </select>
    @error('manufacturer_id')
        <div class="invalid-feedback" id="manufacturer-error">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="name">Név</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $carModel?->name) }}" maxlength="255" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
    @error('name')
        <div class="invalid-feedback" id="name-error">{{ $message }}</div>
    @enderror
</div>
<div class="mb-4">
    <label class="form-label" for="release_year">Megjelenési év</label>
    <input class="form-control @error('release_year') is-invalid @enderror" id="release_year" name="release_year" type="number" min="1886" max="{{ date('Y') }}" step="1" value="{{ old('release_year', $carModel?->release_year) }}" required aria-describedby="year-help @error('release_year') year-error @enderror" @error('release_year') aria-invalid="true" @enderror>
    <div class="form-text" id="year-help">1886 és {{ date('Y') }} közötti egész évszám.</div>
    @error('release_year')
        <div class="invalid-feedback" id="year-error">{{ $message }}</div>
    @enderror
</div>
<div class="d-flex flex-wrap gap-2">
    <button class="btn btn-primary" type="submit" @disabled($manufacturers->isEmpty())>Mentés</button>
    <a class="btn btn-outline-secondary" href="{{ route('car_models.index') }}">Mégse</a>
</div>
