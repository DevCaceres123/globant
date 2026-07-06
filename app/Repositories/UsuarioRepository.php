<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    public function crear(array $datos): Model
    {
        return User::create($datos);
    }

    public function actualizar(int $id, array $datos): Model
    {
        $usuario = User::findOrFail($id);

        $usuario->update($datos);

        return $usuario;
    }

    public function eliminar(int $id): bool
    {
        return User::findOrFail($id)->delete();
    }

    public function buscarPorId(int $id): Model
    {
        return User::findOrFail($id);
    }
}