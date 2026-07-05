<?php
namespace App\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class RolService extends BaseService
{

    /// funcion donde cargaremos el modelo que usaremos.
    protected function model(): Role
    {
        return new Role();
    }

    public function crear(array $datos): Role
    {
        $permisos = $this->extraerPermisos($datos);

        /** @var Role $rol */
        $rol = parent::crear($datos);

        $rol->syncPermissions($permisos ?? []);

        return $rol;
    }


    public function obtenerRolParaEditar(int $id): Role
    {
        $rol = $this->model->select('id', 'name', 'color', 'descripcion')
            ->with([
                'permissions' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->findOrFail($id);

        return $rol;
    }

    public function actualizar(int $id, array $datos): Role
    {

        $permisos = $this->extraerPermisos($datos);
        $rol = parent::actualizar($id, $datos);
        $rol->syncPermissions($permisos);     
        return $rol;

    }


    private function extraerPermisos(array &$datos): array
    {
        $permisos = $datos['permisos'] ?? [];

        unset($datos['permisos']);

        return $permisos;
    }
}