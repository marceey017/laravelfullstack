@extends('layouts.app')

@section('title', 'Gyártók')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Gyártók</h1>
            <p class="text-body-secondary mb-0">Autógyártók és a hozzájuk tartozó modellek.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('manufacturers.create') }}">Új gyártó</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET" action="{{ route('manufacturers.index') }}" role="search">
                <div class="col-md-8">
                    <label class="form-label" for="search">Keresés név vagy ország alapján</label>
                    <input class="form-control" id="search" name="search" value="{{ $search }}" maxlength="100" placeholder="Például: Volkswagen">
                </div>
                <div class="col-md-4 d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit">Keresés</button>
                    @if ($search !== '')
                        <a class="btn btn-outline-secondary" href="{{ route('manufacturers.index') }}">Alaphelyzet</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($manufacturers->count())
        <div class="card shadow-sm overflow-hidden">
            <div class="table-responsive" role="region" aria-label="Gyártók listája" tabindex="0">
                <table class="table table-hover align-middle mb-0">
                    <caption class="visually-hidden">Autógyártók és elérhető műveletek</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Név</th>
                            <th scope="col">Ország</th>
                            <th scope="col">Modellek</th>
                            <th scope="col" class="text-end pe-3">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($manufacturers as $manufacturer)
                            <tr>
                                <th scope="row" class="ps-3 text-break">{{ $manufacturer->name }}</th>
                                <td class="text-break">{{ $manufacturer->country }}</td>
                                <td><a class="badge text-bg-secondary text-decoration-none" href="{{ route('car_models.index', ['manufacturer_id' => $manufacturer->id]) }}" aria-label="{{ $manufacturer->name }}: {{ $manufacturer->car_models_count }} modell">{{ $manufacturer->car_models_count }}</a></td>
                                <td class="pe-3">
                                    <div class="d-flex flex-wrap justify-content-end gap-2 py-1">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('manufacturers.show', $manufacturer) }}">Megnézés</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('manufacturers.edit', $manufacturer) }}">Szerkesztés</a>
                                        <form method="POST" action="{{ route('manufacturers.destroy', $manufacturer) }}" onsubmit="return confirm('Biztosan törlöd a gyártót és az összes hozzá tartozó modellt?')">
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
        @include('partials.pagination', ['paginator' => $manufacturers])
    @else
        <div class="card text-center shadow-sm">
            <div class="card-body py-5">
                <h2 class="h5">Nincs találat.</h2>
                <p class="text-body-secondary mb-3">Módosítsd a keresést, vagy adj hozzá egy új gyártót.</p>
                <a class="btn btn-outline-primary" href="{{ route('manufacturers.create') }}">Új gyártó</a>
            </div>
        </div>
    @endif
@endsection
