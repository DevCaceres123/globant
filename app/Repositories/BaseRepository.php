<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = $this->model();
    }

    abstract protected function model(): Model;

    public function crear(array $datos): Model
    {
        return $this->model->create($datos);
    }

    public function buscarPorId(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function actualizar(int $id, array $datos): Model
    {
        $modelo = $this->buscarPorId($id);

        $modelo->update($datos);

        return $modelo;
    }

    public function eliminar(int $id): bool
    {
        return $this->buscarPorId($id)->delete();
    }
}