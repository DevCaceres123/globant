<?php

namespace App\Http\Controllers\Administrador\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//librerias que se usan en este controlador
use Exception;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\Administrador\Usuario\UsuarioRequest;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

//funciones de apoyo
use App\Traits\HandlesTransactions;
use App\Traits\HasApiResponses;
use App\Services\UsuarioService;
class UsuarioController extends Controller implements HasMiddleware
{
    use HandlesTransactions, HasApiResponses;

    public function __construct(
        protected UsuarioService $usuarioService
    ) {
    }


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


    public function index()
    {
        $rol = Role::select('id', 'name')->get();
        return view('administrador.administrador.usuario', compact('rol'));
    }


    public function listarUsuarios(Request $request)
    {
        $query = User::with(['roles'])->select('id', 'usuario', 'ci', 'nombres', 'apellidos', 'estado', 'email')->orderBy('id', 'desc');

        if (!empty($request->search['value'])) {
            $query->where(function ($q) use ($request) {
                $q->where('ci', 'like', '%' . $request->search['value'] . '%')->orWhere('usuario', 'like', '%' . $request->search['value'] . '%')->orWhere
                ('nombres', 'like', '%' . $request->search['value'] . '%')->orWhere
                    ('apellidos', 'like', '%' . $request->search['value'] . '%')
                    ->orWhereHas('roles', function ($rolQuery) use ($request) {
                        $rolQuery->where('name', 'like', '%' . $request->search['value'] . '%');
                    });
            });
        }

        // Total de registros antes del filtrado
        $recordsTotal = $query->count();

        // Paginación y orden
        $sedes = $query->skip($request->start)->take($request->length)->get();

        // Respuesta
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal, // Ajustar si hay filtros
            'data' => $sedes,
            'permisos' => [
                'editar' => auth()->user()->can('sede.editar'),
                'eliminar' => true,
                'estado' => auth()->user()->can('sede.desactivar'),

            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Guardar datos de usuario
     */
    public function store(UsuarioRequest $request)
    {
        try {
            return $this->transaction(function () use ($request) {
                $this->usuarioService->crear(
                    $request->validated()
                );

                return $this->success('El usuario fue registrado correctamente.');
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

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {

            return $this->transaction(function () use ($id) {
                $datosUsuario = User::select('id', 'nombres', 'apellidos', 'ci', 'email', 'estado', 'usuario')
                    ->with([
                        'roles' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ])
                    ->findOrFail($id);

                return $this->success('datos obtenidos con exito', $datosUsuario);

            });


        } catch (ModelNotFoundException $e) {
            return $this->notFound('usuario no encontrado.');

        } catch (Exception $e) {
            return $this->error('Ocurrió un error inesperado.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsuarioRequest $request, string $id)
    {
        try {

            return $this->transaction(function () use ($request, $id) {

                $this->usuarioService->actualizar($id, $request->validated());

                return $this->success('Datos actualizados correctamente.');

            });

        } catch (ModelNotFoundException $e) {
            return $this->notFound('usuario no encontrado.');

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

                $this->usuarioService->eliminar($id);
                return $this->success("El registro fue eliminado correctamente");

            });

        } catch (ModelNotFoundException $e) {
            return $this->notFound('usuario no encontrado.');

        } catch (Throwable $e) {
            return $this->error('Ocurrió un error inesperado.');
        }
    }


    public function actualizarEstado(string $id, UsuarioRequest $request)
    {
        
        try {
            return $this->transaction(function () use ($id, $request) {
                $this->usuarioService->actualizarEstado($id ,$request->estado);
                return $this->success("El estado del usuario fue actualizado correctamente");
            });
        } catch (ModelNotFoundException $e) {
            return $this->notFound('usuario no encontrado.');

        } catch (Exception $e) {
            return $this->error('Ocurrió un error inesperado.');
        }
    }
}
