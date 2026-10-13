<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $__env->yieldContent('title', 'Autókatalógus') }} | Autókatalógus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-body-tertiary min-vh-100 d-flex flex-column">
    <a class="visually-hidden-focusable btn btn-light position-absolute m-2" href="#content">Ugrás a tartalomhoz</a>
    <header class="bg-dark">
        <nav class="container d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 py-3" aria-label="Fő navigáció">
            <a class="text-white text-decoration-none fs-4 fw-semibold" href="{{ route('manufacturers.index') }}">Autókatalógus</a>
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('manufacturers.*') ? 'active' : 'text-white' }}" href="{{ route('manufacturers.index') }}" @if (request()->routeIs('manufacturers.*')) aria-current="page" @endif>Gyártók</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('car_models.*') ? 'active' : 'text-white' }}" href="{{ route('car_models.index') }}" @if (request()->routeIs('car_models.*')) aria-current="page" @endif>Autómodellek</a>
                </li>
            </ul>
        </nav>
    </header>

    <main id="content" class="container py-4 py-md-5">
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <p class="fw-semibold mb-2">Ellenőrizd a megadott adatokat:</p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-auto border-top bg-white">
        <div class="container py-3 small text-body-secondary">Autókatalógus · Laravel + Bootstrap</div>
    </footer>
</body>
</html>
