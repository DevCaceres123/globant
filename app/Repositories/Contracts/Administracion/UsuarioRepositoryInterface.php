<?php

namespace App\Repositories\Contracts\Administracion;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface UsuarioRepositoryInterface extends BaseRepositoryInterface
{
    public function buscarParaEditar(int $id): Model;

    public function obtenerRoles(): collection;

    public function obtenerTotal(): int;

    public function obtenerTotalActivos(): int;

    public function obtenerTotalInactivos(): int;

    public function obtenerTotalRoles(): int;
}