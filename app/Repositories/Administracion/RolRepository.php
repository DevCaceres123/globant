<?php

namespace App\Repositories\Administracion;

use Spatie\Permission\Models\Role;
use App\Repositories\Contracts\Administracion\RolRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class RolRepository extends BaseRepository implements RolRepositoryInterface
{
    protected function model(): Model
    {
        return new Role();
    }

    public function buscarParaEditar(int $id): Role
    {
        $rol = Role::select('id', 'name', 'color', 'descripcion')
            ->with([
                'permissions' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->findOrFail($id);

        return $rol;
    }

    public function obtenerRolesConPermisos(): Collection
    {
        $roles = Role::select('id', 'name', 'color', 'descripcion', 'updated_at')
            ->withCount('permissions')
            ->with([
                'permissions' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->get();

        return $roles;
    }
}