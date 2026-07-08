<?php

namespace App\Repositories\Contracts\Administracion;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface RolRepositoryInterface extends BaseRepositoryInterface
{
    public function buscarConPermisos(int $id): Model;

    public function obtenerRolesConPermisos(): Collection;

}