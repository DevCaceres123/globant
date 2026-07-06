<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface UsuarioRepositoryInterface
{
    public function crear(array $datos): Model;

    public function actualizar(int $id, array $datos): Model;

    public function eliminar(int $id): bool;

    public function buscarPorId(int $id): Model;
}