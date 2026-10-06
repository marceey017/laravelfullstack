@extends('layouts.app')

@section('title', 'Autómodell szerkesztése')

@section('content')
    <h2>Autómodell szerkesztése</h2>

    <form method="POST" action="{{ route('car_models.update', $carModel) }}">
        @csrf
        @method('PUT')

        <p>
            <label for="manufacturer_id">Gyártó:</label><br>
            <select id="manufacturer_id" name="manufacturer_id" required>
                @foreach ($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}" @selected((string) old('manufacturer_id', $carModel->manufacturer_id) === (string) $manufacturer->id)>
                        {{ $manufacturer->name }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label for="name">Név:</label><br>
            <input id="name" name="name" value="{{ old('name', $carModel->name) }}" required>
        </p>

        <p>
            <label for="release_year">Megjelenési év:</label><br>
            <input id="release_year" name="release_year" type="number" min="1886" max="{{ date('Y') }}" value="{{ old('release_year', $carModel->release_year) }}" required>
        </p>

        <button type="submit">Mentés</button>
        <a href="{{ route('car_models.index') }}">Mégse</a>
    </form>
@endsection
