@extends('layouts.app')

@section('title', 'Új gyártó')

@section('content')
    <h2>Új gyártó</h2>

    <form method="POST" action="{{ route('manufacturers.store') }}">
        @csrf

        <p>
            <label for="name">Név:</label><br>
            <input id="name" name="name" value="{{ old('name') }}" required>
        </p>

        <p>
            <label for="country">Ország:</label><br>
            <input id="country" name="country" value="{{ old('country') }}" required>
        </p>

        <button type="submit">Mentés</button>
        <a href="{{ route('manufacturers.index') }}">Mégse</a>
    </form>
@endsection
