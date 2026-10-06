@extends('layouts.app')

@section('title', 'Autómodellek')

@section('content')
    <h2>Autómodellek</h2>

    <p><a href="{{ route('car_models.create') }}">Új autómodell</a></p>

    <form method="GET" action="{{ route('car_models.index') }}">
        <label for="search">Keresés:</label>
        <input id="search" name="search" value="{{ $search }}">

        <label for="manufacturer_id">Gyártó:</label>
        <select id="manufacturer_id" name="manufacturer_id">
            <option value="">Mindegyik</option>
            @foreach ($manufacturers as $manufacturer)
                <option value="{{ $manufacturer->id }}" @selected($manufacturerId === $manufacturer->id)>
                    {{ $manufacturer->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Szűrés</button>

        @if ($search !== '' || $manufacturerId !== null)
            <a href="{{ route('car_models.index') }}">Törlés</a>
        @endif
    </form>

    @if ($carModels->count())
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Gyártó</th>
                    <th>Megjelenési év</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carModels as $carModel)
                    <tr>
                        <td>{{ $carModel->name }}</td>
                        <td>{{ $carModel->manufacturer->name }}</td>
                        <td>{{ $carModel->release_year }}</td>
                        <td>
                            <a href="{{ route('car_models.show', $carModel) }}">Megnézés</a>
                            |
                            <a href="{{ route('car_models.edit', $carModel) }}">Szerkesztés</a>
                            |
                            <form method="POST" action="{{ route('car_models.destroy', $carModel) }}">
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
            @if ($carModels->previousPageUrl())
                <a href="{{ $carModels->previousPageUrl() }}">← Előző</a>
            @endif

            Oldal {{ $carModels->currentPage() }} / {{ $carModels->lastPage() }}

            @if ($carModels->nextPageUrl())
                <a href="{{ $carModels->nextPageUrl() }}">Következő →</a>
            @endif
        </p>
    @else
        <p>Nincs találat.</p>
    @endif
@endsection
