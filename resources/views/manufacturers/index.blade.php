@extends('layouts.app')

@section('title', 'Gyártók')

@section('content')
    <h2>Gyártók</h2>

    <p><a href="{{ route('manufacturers.create') }}">Új gyártó</a></p>

    <form method="GET" action="{{ route('manufacturers.index') }}">
        <label for="search">Keresés:</label>
        <input id="search" name="search" value="{{ $search }}">
        <button type="submit">Keresés</button>
        @if ($search !== '')
            <a href="{{ route('manufacturers.index') }}">Törlés</a>
        @endif
    </form>

    @if ($manufacturers->count())
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Ország</th>
                    <th>Modellek</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($manufacturers as $manufacturer)
                    <tr>
                        <td>{{ $manufacturer->name }}</td>
                        <td>{{ $manufacturer->country }}</td>
                        <td>{{ $manufacturer->car_models_count }}</td>
                        <td>
                            <a href="{{ route('manufacturers.show', $manufacturer) }}">Megnézés</a>
                            |
                            <a href="{{ route('manufacturers.edit', $manufacturer) }}">Szerkesztés</a>
                            |
                            <form method="POST" action="{{ route('manufacturers.destroy', $manufacturer) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Biztosan törlöd?')">Törlés</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>
            @if ($manufacturers->previousPageUrl())
                <a href="{{ $manufacturers->previousPageUrl() }}">← Előző</a>
            @endif

            Oldal {{ $manufacturers->currentPage() }} / {{ $manufacturers->lastPage() }}

            @if ($manufacturers->nextPageUrl())
                <a href="{{ $manufacturers->nextPageUrl() }}">Következő →</a>
            @endif
        </p>
    @else
        <p>Nincs találat.</p>
    @endif
@endsection
