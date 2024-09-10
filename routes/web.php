<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\DestinoController;

Route::get('/', [ViajeController::class, 'index']);

Route::get('viaje/obtenerOrigen/{id}', [ViajeController::class, 'obtenerOrigen']);

Route::post('viaje/reservar', [ViajeController::class, 'viajeMiTaxi']);

Route::get('viaje/obtenerDestinos', [DestinoController::class, 'index']);

Route::get('viaje/obtenerDestinoId/{id}', [DestinoController::class, 'obtenerDestinoId']);
