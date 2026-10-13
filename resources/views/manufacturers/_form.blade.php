<div class="mb-3">
    <label class="form-label" for="name">Név</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $manufacturer?->name) }}" maxlength="255" required @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
    @error('name')
        <div class="invalid-feedback" id="name-error">{{ $message }}</div>
    @enderror
</div>
<div class="mb-4">
    <label class="form-label" for="country">Ország</label>
    <input class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $manufacturer?->country) }}" maxlength="255" required @error('country') aria-invalid="true" aria-describedby="country-error" @enderror>
    @error('country')
        <div class="invalid-feedback" id="country-error">{{ $message }}</div>
    @enderror
</div>
<div class="d-flex flex-wrap gap-2">
    <button class="btn btn-primary" type="submit">Mentés</button>
    <a class="btn btn-outline-secondary" href="{{ route('manufacturers.index') }}">Mégse</a>
</div>
