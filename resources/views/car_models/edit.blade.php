@extends('layouts.app')

@section('title', 'Autómodell szerkesztése')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <a class="d-inline-block mb-3" href="{{ route('car_models.index') }}">Vissza az autómodellekhez</a>
            <h1 class="h2 mb-4">Autómodell szerkesztése</h1>
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('car_models.update', $carModel) }}">
                        @csrf
                        @method('PUT')
                        @include('car_models._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
