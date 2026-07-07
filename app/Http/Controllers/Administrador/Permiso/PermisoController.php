<?php

namespace App\Http\Controllers\Administrador\Permiso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//librerias que se usan en este controlador

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Administrador\Rol\RolRquest;
use Spatie\Permission\Models\Permission;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;


//funciones de apoyo
use App\Traits\HandlesTransactions;
use App\Traits\HasApiResponses;


class PermisoController extends Controller implements HasMiddleware
{
    

    use HandlesTransactions, HasApiResponses;

    public static function middleware(): array
    {
        // return [
        //     new Middleware('permission:usuario.listar', only: ['index', 'listarUsuarios']),
        //     new Middleware('permission:usuario.crear',  only: ['store']),
        //     new Middleware('permission:usuario.editar', only: ['update']),
        //     new Middleware('permission:usuario.eliminar', only: ['destroy']),
        // ];

        return [];
    }

    

    /**
     * Módulo de permisos (SOLO LECTURA).
     *
     * Los permisos son la fuente única de config/permisos.php + seeder; aquí
     * solo se listan para consulta/auditoría, mostrando qué roles tiene cada uno.
     * Se indexan por 'name' para poder buscarlos directamente desde el config
     * en la vista (clave = "modulo.accion").
     */
    public function index()
    {
        $permisos = Permission::withCount('roles')
            ->with(['roles' => fn ($query) => $query->select('id', 'name', 'color')])
            ->get()
            ->keyBy('name');

        // Todas las claves "modulo.accion" definidas en el config (fuente única)
        $definidos = collect(config('permisos.estructura'))
            ->flatMap(fn ($modulos) => collect($modulos)
                ->flatMap(fn ($meta, $clave) => collect($meta['acciones'])
                    ->map(fn ($accion) => "{$clave}.{$accion}")));

        $resumen = [
            'total'      => $definidos->count(),
            'modulos'    => collect(config('permisos.estructura'))->sum(fn ($modulos) => count($modulos)),
            'sinSembrar' => $definidos->reject(fn ($name) => $permisos->has($name))->count(),
        ];

        return view('administrador.administrador.permiso', compact('permisos', 'resumen'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
