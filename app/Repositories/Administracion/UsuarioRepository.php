<?php

namespace App\Repositories\Administracion;

use App\Models\User;
use App\Repositories\Contracts\Administracion\UsuarioRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UsuarioRepository extends BaseRepository implements UsuarioRepositoryInterface
{
    protected function model(): Model
    {
        return new User();
    }

    public function buscarParaEditar(int $id): User
    {
        $usuario = User::select('id', 'nombres', 'apellidos', 'ci', 'email', 'estado', 'usuario')
            ->with([
                'roles' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->findOrFail($id);

        return $usuario;
    }
}