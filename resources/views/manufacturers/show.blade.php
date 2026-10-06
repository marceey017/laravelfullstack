@extends('layouts.app')

@section('title', $manufacturer->name)

@section('content')
    <h2>{{ $manufacturer->name }}</h2>

    <p><strong>Ország:</strong> {{ $manufacturer->country }}</p>

    <p>
        <a href="{{ route('manufacturers.edit', $manufacturer) }}">Szerkesztés</a>
        |
        <a href="{{ route('manufacturers.index') }}">Vissza</a>
    </p>

    <h3>Modellek</h3>

    @if ($manufacturer->carModels->count())
        <ul>
            @foreach ($manufacturer->carModels as $carModel)
                <li>
                    <a href="{{ route('car_models.show', $carModel) }}">{{ $carModel->name }}</a>
                    ({{ $carModel->release_year }})
                </li>
            @endforeach
        </ul>
    @else
        <p>Ehhez a gyártóhoz még nincs modell.</p>
    @endif
@endsection
