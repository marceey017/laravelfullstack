<?php

use App\Http\Controllers\ManufacturerController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/manufacturers');
Route::resource('manufacturers', ManufacturerController::class);

// A car_models resource route-ot a második backendes adja hozzá.
