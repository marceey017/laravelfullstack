<?php

use App\Http\Controllers\CarModelController;
use App\Http\Controllers\ManufacturerController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/manufacturers');
Route::resource('manufacturers', ManufacturerController::class);

Route::resource('car_models', CarModelController::class);
