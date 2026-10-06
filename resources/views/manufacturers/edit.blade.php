@extends('layouts.app')

@section('title', 'Gyártó szerkesztése')

@section('content')
    <h2>Gyártó szerkesztése</h2>

    <form method="POST" action="{{ route('manufacturers.update', $manufacturer) }}">
        @csrf
        @method('PUT')

        <p>
            <label for="name">Név:</label><br>
            <input id="name" name="name" value="{{ old('name', $manufacturer->name) }}" required>
        </p>

        <p>
            <label for="country">Ország:</label><br>
            <input id="country" name="country" value="{{ old('country', $manufacturer->country) }}" required>
        </p>

        <button type="submit">Mentés</button>
        <a href="{{ route('manufacturers.index') }}">Mégse</a>
    </form>
@endsection
