<?php
namespace App\Services;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class RolService extends BaseService
{

    /// funcion donde cargaremos el modelo que usaremos.
    protected function model(): Model
    {
        return new Role();
    }

    public function crear(array $datos): Model
    {
        $permisos=null;
        if (isset($datos['permisos'])) {
            $permisos = $datos['permisos'];
            unset($datos['permisos']);
        }

        /** @var Role $rol */
        $rol = parent::crear($datos);

        $rol->syncPermissions($permisos ?? []);

        return $rol;
    }


    public function obtenerRol(int $id): Model
    {
        $rol=$this->model->select('id','name','color','descripcion')
        ->with([
            'permissions'=> function ($query){
                $query->select('id','name');
            }
        ])
        ->where('id',$id)
        ->firstOrFail();

        return $rol;
    }

    public function actualizar(int $id, array $datos): Model{

        $permisos=null;
        if (isset($datos['permisos'])) {
            $permisos = $datos['permisos'];
            unset($datos['permisos']);
        }
        $rol=parent::actualizar($id,$datos);
        $rol->syncPermissions($permisos ?? []);
        return $rol;

    }
}