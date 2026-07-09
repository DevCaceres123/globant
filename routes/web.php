<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Administrador\Usuario\UsuarioController;
use App\Http\Controllers\Administrador\Rol\RolController;
use App\Http\Controllers\Administrador\Permiso\PermisoController;
use App\Http\Controllers\Resumen\InicioController;


Route::get('/', function () {
    return redirect()->route('login');
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

    Route::get('/', [InicioController::class, 'index']);

    //Ruta de LOGIN cuando SI estamos logeados
    Route::controller(LoginController::class)->group(function () {
        Route::post('salir', 'salir')->name('salir');        
    });


    //RUTAS PARA EL MODULO DE ADMINISTRADORES

    //Ruta para los usuarios
    Route::controller(UsuarioController::class)->group(function () {               
        Route::get('listarUsuarios', 'listarUsuarios')->name('listarUsuarios');
        Route::patch('actualizarEstado/{id_usuario}', 'actualizarEstado')->name('actualizarEstado');
        Route::resource('usuario', UsuarioController::class);        
    }); 

    //Ruta para los roles
    Route::controller(RolController::class)->group(function () { 
        Route::resource('rol', RolController::class);        
    }); 

    //Ruta para los roles
    Route::controller(PermisoController::class)->group(function () {  
        Route::resource('permiso', PermisoController::class);        
    }); 
});

