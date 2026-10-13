@extends('layouts.app')

@section('title', 'Gyártó szerkesztése')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <a class="d-inline-block mb-3" href="{{ route('manufacturers.index') }}">Vissza a gyártókhoz</a>
            <h1 class="h2 mb-4">Gyártó szerkesztése</h1>
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('manufacturers.update', $manufacturer) }}">
                        @csrf
                        @method('PUT')
                        @include('manufacturers._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
