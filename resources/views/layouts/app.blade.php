<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Autókatalógus')</title>
</head>
<body>
    <header>
        <h1>Autókatalógus</h1>
        <nav>
            <a href="{{ route('manufacturers.index') }}">Gyártók</a>
            |
            <a href="{{ route('car_models.index') }}">Autómodellek</a>
        </nav>
        <hr>
    </header>

    @if (session('success'))
        <p><strong>{{ session('success') }}</strong></p>
    @endif

    @if ($errors->any())
        <div>
            <strong>Hiba történt:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
