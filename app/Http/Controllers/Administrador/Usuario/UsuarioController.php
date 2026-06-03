<?php

namespace App\Http\Controllers\Administrador\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//librerias de el contralador
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (!auth()->user()->can('sede.inicio')) {
        //     return redirect()->route('inicio');
        // }
        return view('administrador.administrador.usuario');
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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

     public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }
}
