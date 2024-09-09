<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViajeController;

Route::get('/', [ViajeController::class, 'index']);

Route::post('create', [ViajeController::class, 'create'])->name('viaje.create');;
