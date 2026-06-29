<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UsuarioService extends BaseService
{

    /// funcion donde cargaremos el modelo que usaremos.
    protected function model(): Model
    {
        return new User();
    }

    public function crear(array $datos): Model
    {
        $rol = $datos['rol'];

        unset($datos['rol']);
        /** @var User $usuario */
        $usuario = parent::crear($datos);

        $usuario->assignRole($rol);

        return $usuario;
    }

    public function actualizar(int $id, array $datos): Model
    {
        $rol = $datos['rol'];

        unset($datos['rol']);
        if (empty($datos['password'])) {

            unset($datos['password']);

        }
        /** @var User $usuario */
        $usuario = parent::actualizar($id, $datos);

        $usuario->syncRoles($rol);

        return $usuario;
    }


    public function actualizarEstado(int $id, string $estado): Model
    {
        /** @var User $usuario */
        $usuario=parent::actualizar($id , ['estado'=>$estado]);

        return $usuario;
    }
}