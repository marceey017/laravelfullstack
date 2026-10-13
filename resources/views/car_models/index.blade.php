@extends('layouts.app')

@section('title', 'Autómodellek')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Autómodellek</h1>
            <p class="text-body-secondary mb-0">Böngéssz a modellek között, vagy szűrj gyártóra.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('car_models.create') }}">Új autómodell</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET" action="{{ route('car_models.index') }}" role="search">
                <div class="col-md-5">
                    <label class="form-label" for="search">Keresés</label>
                    <input class="form-control" id="search" name="search" value="{{ $search }}" maxlength="100" placeholder="Például: Golf">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="manufacturer_id">Gyártó</label>
                    <select class="form-select" id="manufacturer_id" name="manufacturer_id">
                        <option value="">Mindegyik</option>
                        @foreach ($manufacturers as $manufacturer)
                            <option value="{{ $manufacturer->id }}" @selected($manufacturerId === $manufacturer->id)>{{ $manufacturer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit">Szűrés</button>
                    @if ($search !== '' || $manufacturerId !== null)
                        <a class="btn btn-outline-secondary" href="{{ route('car_models.index') }}">Alaphelyzet</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($carModels->count())
        <div class="card shadow-sm overflow-hidden">
            <div class="table-responsive" role="region" aria-label="Autómodellek listája" tabindex="0">
                <table class="table table-hover align-middle mb-0">
                    <caption class="visually-hidden">Autómodellek és elérhető műveletek</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Név</th>
                            <th scope="col">Gyártó</th>
                            <th scope="col">Megjelenési év</th>
                            <th scope="col" class="text-end pe-3">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carModels as $carModel)
                            <tr>
                                <th scope="row" class="ps-3 text-break">{{ $carModel->name }}</th>
                                <td class="text-break"><a href="{{ route('manufacturers.show', $carModel->manufacturer) }}">{{ $carModel->manufacturer->name }}</a></td>
                                <td>{{ $carModel->release_year }}</td>
                                <td class="pe-3">
                                    <div class="d-flex flex-wrap justify-content-end gap-2 py-1">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('car_models.show', $carModel) }}">Megnézés</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('car_models.edit', $carModel) }}">Szerkesztés</a>
                                        <form method="POST" action="{{ route('car_models.destroy', $carModel) }}" onsubmit="return confirm('Biztosan törlöd ezt az autómodellt?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Törlés</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('partials.pagination', ['paginator' => $carModels])
    @else
        <div class="card text-center shadow-sm">
            <div class="card-body py-5">
                <h2 class="h5">Nincs találat.</h2>
                <p class="text-body-secondary mb-3">Módosítsd a szűrőket, vagy adj hozzá egy új autómodellt.</p>
                <a class="btn btn-outline-primary" href="{{ route('car_models.create') }}">Új autómodell</a>
            </div>
        </div>
    @endif
@endsection
