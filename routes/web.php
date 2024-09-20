<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\AdminController;

Route::get('/', [ViajeController::class, 'index'])->name('reserva.index');

Route::get('viaje/reservaExitosa', [ViajeController::class, 'reservaExitosa']);

Route::get('viaje/obtenerOrigen/{id}', [ViajeController::class, 'obtenerOrigen']);

Route::post('viaje/reservar', [ViajeController::class, 'viajeMiTaxi']);

Route::get('viaje/obtenerDestinos', [DestinoController::class, 'index']);

Route::get('viaje/obtenerDestinoId/{id}', [DestinoController::class, 'obtenerDestinoId']);

//Routes Admin
Route::get('admin/home', [AdminController::class, 'index'])->name('admin.home');

Route::get('login', [AdminController::class, 'index']);

Route::get('logout', [AdminController::class, 'logout'])->name("admin.logout");

Route::post('admin/entrar', [AdminController::class, 'login'])->name("admin.login");

Route::post('admin/generarTicket', [AdminController::class, 'generarTicket'])->name("admin.generar");;

Route::post('admin/encrypt', [AdminController::class, 'encriptar']);

Route::post('admin/webHookMyRide/{id_empresa}',[AdminController::class, 'webHookMyRide']);