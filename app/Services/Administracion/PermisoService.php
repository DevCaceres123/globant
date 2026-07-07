<?php
namespace App\Services\Administracion;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermisoService
{

    /// funcion donde cargaremos el modelo que usaremos.
    protected function model(): Model
    {
        return new Permission();
    }

    
}