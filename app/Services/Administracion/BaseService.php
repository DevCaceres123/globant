<?php

namespace App\Services\Administracion;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
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

    public function eliminar(int $id): bool
    {
        $model = $this->buscarPorId($id);
        return $model->delete();
    }

    public function actualizar(int $id, array $datos): Model
    {
        $modelo = $this->buscarPorId($id);

        $modelo->update($datos);

        return $modelo;
    }

    public function listar(array $columnas):Model
    {
        $modelo=$this->model->get();
        return $modelo;
        
    }

}