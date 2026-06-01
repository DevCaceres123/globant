<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Administrador\Usuario\UsuarioController;


Route::get('/', function () {
    return view('welcome');
});

// RUTAS DONDE NO EXISTE EL INICIO DE SESION
Route::prefix('/')->middleware('guest')->group(function () {

    //Ruta de LOGIN cuando  NO estamos logeados
    Route::controller(LoginController::class)->group(function () {
        Route::get('login','showLogin')->name('login');
        Route::post('ingresar', 'ingresar')->name('ingresar');
        
    });
});


//RUTAS DONDE EXISTE EL INICIO DE SESSION

Route::prefix('/admin')->middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('administrador.dashboard');
    });
    //Ruta de LOGIN cuando SI estamos logeados
    Route::controller(LoginController::class)->group(function () {
        Route::post('salir', 'salir')->name('salir');        
    });


    //Ruta para el modulo de administradores
    Route::controller(UsuarioController::class)->group(function () {
        Route::get('usuarios', 'index')->name('usuarios');        
    });
});

