<?php

namespace App\Http\Controllers\Administrador\Rol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//librerias que se usan en este controlador

use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Administrador\Rol\RolRquest;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

//funciones de apoyo
use App\Traits\HandlesTransactions;
use App\Traits\HasApiResponses;
use App\Services\Administracion\RolService;

class RolController extends Controller implements HasMiddleware
{
    use HandlesTransactions, HasApiResponses;

    public function __construct(
        protected RolService $rolService
    ) {
    }


    public static function middleware(): array
    {
        return [
            new Middleware('permission:rol.ver', only: ['index']),
            new Middleware('permission:rol.crear', only: ['store']),
            new Middleware('permission:rol.editar', only: ['update', 'edit']),
            new Middleware('permission:rol.eliminar', only: ['destroy']),
            new Middleware('permission:rol.visualizar', only: ['show']),
        ];


    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->rolService->obtenerRolesConPermisos();

        return view('administrador.administrador.rol', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RolRquest $request)
    {
        try {
            return $this->transaction(function () use ($request) {

                $this->rolService->crear($request->validated());
                return $this->success('El Rol fue registrado correctamente.');
            });
        } catch (Throwable $e) {
            return $this->error('Ocurrió un error inesperado.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->transaction(function () use ($id) {

                $rol = $this->rolService->obtenerRolParaEditar($id);
                return $this->success('Datos obtenidos correctamente.', $rol);
            });
        } catch (ModelNotFoundException $e) {
            return $this->notFound('rol no encontrado.');

        } catch (Throwable $e) {
            return $this->error('Ocurrió un error inesperado.');
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        try {
            return $this->transaction(function () use ($id) {

                $rol = $this->rolService->obtenerRolParaEditar($id);
                return $this->success('Datos obtenidos correctamente.', $rol);
            });
        } catch (ModelNotFoundException $e) {
            return $this->notFound('rol no encontrado.');

        } catch (Throwable $e) {
            return $this->error('Ocurrió un error inesperado.');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolRquest $request, string $id)
    {
        try {
            return $this->transaction(function () use ($request, $id) {

                $this->rolService->actualizar($id, $request->validated());
                return $this->success('El Rol fue editado correctamente.');
            });
        } catch (ModelNotFoundException $e) {
            return $this->notFound('rol no encontrado.');

        } catch (Throwable $e) {
            return $this->error('Ocurrió un error inesperado.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return $this->transaction(function () use ($id) {

                $this->rolService->eliminar($id);
                return $this->success('El Rol fue eliminado correctamente.');
            });
        } catch (ModelNotFoundException $e) {
            return $this->notFound('rol no encontrado.');

        } catch (Throwable $e) {
            return $this->error('Ocurrió un error inesperado.');
        }

    }
}
