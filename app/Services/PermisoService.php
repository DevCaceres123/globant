<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermisoService extends BaseService
{

    /// funcion donde cargaremos el modelo que usaremos.
    protected function model(): Model
    {
        return new Permission();
    }

    
}