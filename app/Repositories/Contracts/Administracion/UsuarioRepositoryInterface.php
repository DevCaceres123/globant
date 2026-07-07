<?php

namespace App\Repositories\Contracts\Administracion;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\BaseRepositoryInterface;

interface UsuarioRepositoryInterface extends BaseRepositoryInterface
{
    public function buscarParaEditar(int $id): Model;
}