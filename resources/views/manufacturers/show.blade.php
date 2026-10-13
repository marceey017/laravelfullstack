@extends('layouts.app')

@section('title', $manufacturer->name)

@section('content')
    <a class="d-inline-block mb-3" href="{{ route('manufacturers.index') }}">Vissza a gyártókhoz</a>
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                <div>
                    <h1 class="h2 text-break mb-3">{{ $manufacturer->name }}</h1>
                    <p class="mb-0"><span class="text-body-secondary">Ország:</span> {{ $manufacturer->country }}</p>
                </div>
                <a class="btn btn-outline-primary" href="{{ route('manufacturers.edit', $manufacturer) }}">Szerkesztés</a>
            </div>
        </div>
    </div>

    <h2 class="h4 mb-3">Modellek <span class="badge text-bg-secondary">{{ $manufacturer->carModels->count() }}</span></h2>
    @if ($manufacturer->carModels->count())
        <div class="card shadow-sm overflow-hidden">
            <div class="table-responsive" role="region" aria-label="A gyártó modelljei" tabindex="0">
                <table class="table table-hover align-middle mb-0">
                    <caption class="visually-hidden">{{ $manufacturer->name }} autómodelljei</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Név</th>
                            <th scope="col">Megjelenési év</th>
                            <th scope="col" class="text-end pe-3">Részletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($manufacturer->carModels as $carModel)
                            <tr>
                                <th scope="row" class="ps-3 text-break">{{ $carModel->name }}</th>
                                <td>{{ $carModel->release_year }}</td>
                                <td class="text-end pe-3"><a class="btn btn-sm btn-outline-primary" href="{{ route('car_models.show', $carModel) }}">Megnézés</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info">Ehhez a gyártóhoz még nincs modell.</div>
    @endif
@endsection
