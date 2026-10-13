@extends('layouts.app')

@section('title', $carModel->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a class="d-inline-block mb-3" href="{{ route('car_models.index') }}">Vissza az autómodellekhez</a>
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h2 text-break mb-4">{{ $carModel->name }}</h1>
                    <dl class="row mb-4">
                        <dt class="col-sm-4 text-body-secondary">Gyártó</dt>
                        <dd class="col-sm-8 text-break"><a href="{{ route('manufacturers.show', $carModel->manufacturer) }}">{{ $carModel->manufacturer->name }}</a></dd>
                        <dt class="col-sm-4 text-body-secondary">Megjelenési év</dt>
                        <dd class="col-sm-8">{{ $carModel->release_year }}</dd>
                    </dl>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-primary" href="{{ route('car_models.edit', $carModel) }}">Szerkesztés</a>
                        <a class="btn btn-outline-secondary" href="{{ route('car_models.index') }}">Vissza a listához</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
