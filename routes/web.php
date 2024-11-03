<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VehiculosController;
use App\Http\Controllers\OperadorController;

#region [Vistas]
    //Página Principal
    Route::get('/', [ViajeController::class, 'index'])->name('reserva.index');
    //Página Login
    Route::get('login', [AdminController::class, 'index']);
#endregion

#region [Consumo Auth]
    Route::group(['as' => 'auth.','prefix' => 'auth'], function () {
        //Loguearse
        Route::post('entrar', [AdminController::class, 'login'])->name("login");
        //Desloguearse
        Route::get('logout', [AdminController::class, 'logout'])->name("logout");
    });
#endregion

#region [Consumo Admin]
    Route::group(['as' => 'admin.','prefix' => 'admin'], function () {
        #region [Páginas Admin]
            //Home
            Route::get('/', [AdminController::class, 'index'])->name('home');
            //Vehiculo
            Route::get('vehiculos', [VehiculosController::class, 'index'])->name('vehiculos');
            //Destinos
            Route::get('destinos', [DestinoController::class, 'index'])->name('destinos');
        #endregion
        #region [Rutas Api]
            Route::group(['as' => 'api.','prefix' => 'api'], function () {
                //CRUD Home
                Route::post('generarTicket', [AdminController::class, 'generarTicket'])->name("generar");
                Route::post('asignarOperadorAViaje', [AdminController::class, 'asignarOperadorAViaje'])->name("asignarOperadorAViaje");  
                Route::post('agregarNuevoTurno', [AdminController::class, 'agregarNuevoTurno'])->name('agregarNuevoTurno');   
                Route::get('getTurnos', [AdminController::class, 'obtenerTurnosAsync'])->name('obtenerTurnosAsync');           
                //CRUD Destino
                Route::post('obtenerDestinoId', [DestinoController::class, 'obtenerDestinoIdAdmin'])->name('getDestinoId');
                Route::post('guardarDestino', [DestinoController::class, 'guardarDestino'])->name('guardarDestino');
                //CRUD Vehiculo
                Route::post("guardarVehiculo",[VehiculosController::class, 'guardarVehiculo'])->name("guardarVehiculo");
                Route::post('obtenerVehiculosPorId', [VehiculosController::class, 'obtenerVehiculosPorId'])->name('getVehiculoId');
                //CRUD Operador
                Route::post("guardarOperador",[OperadorController::class, 'guardarOperador'])->name("guardarOperador");
                Route::post('obtenerVehiculoOperadores', [OperadorController::class, 'obtenerVehiculoOperadores'])->name('getVehiculoOperadores');
                Route::post('asignarOperadorVehiculo', [OperadorController::class, 'asignarOperadorVehiculo'])->name('asignarOperadorVehiculo');
                Route::post('obtenerOperadorPorId', [OperadorController::class, 'obtenerOperadorPorId'])->name('getOperadorId');

            });
        #endregion 
    });      
#endregion

#region [Consumo Sitio]
    $router->group(['prefix' => 'sitio'], function () use ($router) {
        //Rutas ViajeController
        $router->group(['prefix' => 'viaje'], function () use ($router) {
            $router->get('obtenerOrigen/{id}', [ViajeController::class, 'obtenerOrigen']);
            $router->post('reservar', [ViajeController::class, 'viajeMiTaxi']);
        });
        //Rutas DestinoController
        $router->group(['prefix' => 'destino'], function () use ($router) {
            $router->get('obtenerDestinos', [DestinoController::class, 'obtenerDestinos']);
            $router->get('viaje/obtenerDestinoId/{id}', [DestinoController::class, 'obtenerDestinoId']);
        });

    });
#endregion

#region [WeebHook Myride]
    Route::post('admin/webHookMyRide/{id_empresa}',[AdminController::class, 'webHookMyRide']);
#endregion

// Route::get('viaje/obtenerOrigen/{id}', [ViajeController::class, 'obtenerOrigen']);

// Route::post('viaje/reservar', [ViajeController::class, 'viajeMiTaxi']);

// Route::get('viaje/obtenerDestinos', [DestinoController::class, 'obtenerDestinos']);

// Route::get('viaje/obtenerDestinoId/{id}', [DestinoController::class, 'obtenerDestinoId']);

//Página Reserva Exitosa
//Route::get('viaje/reservaExitosa', [ViajeController::class, 'reservaExitosa']);
