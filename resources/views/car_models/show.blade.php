@extends('layouts.app')

@section('title', $carModel->name)

@section('content')
    <h2>{{ $carModel->name }}</h2>

    <p><strong>Gyártó:</strong> <a href="{{ route('manufacturers.show', $carModel->manufacturer) }}">{{ $carModel->manufacturer->name }}</a></p>
    <p><strong>Megjelenési év:</strong> {{ $carModel->release_year }}</p>

    <p>
        <a href="{{ route('car_models.edit', $carModel) }}">Szerkesztés</a>
        |
        <a href="{{ route('car_models.index') }}">Vissza</a>
    </p>
@endsection
