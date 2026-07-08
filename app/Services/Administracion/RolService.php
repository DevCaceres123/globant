<?php
namespace App\Services\Administracion;

use App\Repositories\Contracts\Administracion\RolRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
class RolService
{


    public function __construct(protected RolRepositoryInterface $rolRepository)
    {
    }

    public function crear(array $datos): Role
    {
        $permisos = $this->extraerPermisos($datos);

        /** @var Role $rol */
        $rol = $this->rolRepository->crear($datos);

        $rol->syncPermissions($permisos ?? []);

        return $rol;
    }


    public function obtenerRolParaEditar(int $id): Role
    {
        $rol = $this->rolRepository->buscarConPermisos($id);

        return $rol;
    }

    public function actualizar(int $id, array $datos): Role
    {

        $permisos = $this->extraerPermisos($datos);
        $rol = $this->rolRepository->actualizar($id, $datos);
        $rol->syncPermissions($permisos);
        return $rol;

    }

    public function eliminar(int $id): bool
    {
        $rol = $this->rolRepository->buscarPorId($id);

        if ($rol->users()->exists()) {
            throw new \Exception('No se puede eliminar el rol porque está asignado a uno o más usuarios.');
        }
        return $this->rolRepository->eliminar($id);
        return $this->rolRepository->eliminar($id);
    }

    public function obtenerRolesConPermisos(): Collection
    {
        return $this->rolRepository->obtenerRolesConPermisos();
    }


    private function extraerPermisos(array &$datos): array
    {
        $permisos = $datos['permisos'] ?? [];

        unset($datos['permisos']);

        return $permisos;
    }
}