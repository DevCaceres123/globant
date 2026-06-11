<?php

namespace App\Http\Controllers\Administrador\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//librerias que se usan en este controlador
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Administrador\Usuario\UsuarioRequest;

class UsuarioController extends Controller implements HasMiddleware
{
    private $mensaje;
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
        $rol=Role::select('id', 'name')->get();
        return view('administrador.administrador.usuario',compact('rol'));
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
            DB::beginTransaction();

            $usuario = User::create([
                'nombres'   => $request->nombres,
                'apellidos' => $request->apellidos,
                'ci'        => $request->ci,
                'email'     => $request->email,
                'usuario'   => $request->usuario,
                'password'  => $request->password,
                'estado'    => $request->estado,
            ]);

            $usuario->assignRole($request->rol);

            DB::commit();

            $this->mensaje('exito', 'El usuario fue registrado correctamente.');
            return response()->json($this->mensaje, 200);

        } catch (Exception $e) {
            DB::rollBack();
            $this->mensaje('error', 'Error: ' . $e->getMessage());
            return response()->json($this->mensaje, 200);
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
        try {

            $usuario = User::where('id', $id)->first();

            if (!$usuario) {
                throw new Exception("no existe el usuario");
            }

            DB::beginTransaction();

            $usuario->delete();

            DB::commit();

            $this->mensaje('exito', "El registro fue eliminado correctamente");
            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {

            DB::rollBack();
            $this->mensaje("error", "Error " . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function actualizarEstado(string $id, Request $request)
    {

        try {

            $usuario = User::find($id);

            if (!$usuario) {
                throw new Exception("no existe el usuario");
            }

            DB::beginTransaction();

            $usuario->estado = $request->estado;
            $usuario->save();

            DB::commit();

            $this->mensaje('exito', "El estado del usuario fue actualizado correctamente");
            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {

            DB::rollBack();
            $this->mensaje("error", "Error " . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }
}
