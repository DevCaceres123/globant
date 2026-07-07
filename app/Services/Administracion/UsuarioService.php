<?php
namespace App\Services\Administracion;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\Administracion\UsuarioRepositoryInterface;
class UsuarioService
{

    public function __construct(protected UsuarioRepositoryInterface $usuarioRepository){}

    public function crear(array $datos): User
    {
        $rol = $datos['rol'];

        unset($datos['rol']);
        /** @var User $usuario */
        $usuario = $this->usuarioRepository->crear($datos);

        $usuario->assignRole($rol);

        return $usuario;
    }

    public function actualizar(int $id, array $datos): User
    {
        $rol = $datos['rol'];

        unset($datos['rol']);
        if (empty($datos['password'])) {

            unset($datos['password']);

        }
        /** @var User $usuario */
        $usuario = $this->usuarioRepository->actualizar($id, $datos);

        $usuario->syncRoles($rol);

        return $usuario;
    }


    public function actualizarEstado(int $id, string $estado): User
    {
        /** @var User $usuario */
        $usuario = $this->usuarioRepository->actualizar($id, ['estado' => $estado]);

        return $usuario;
    }

    public function obtenerUsuarioParaEditar(int $id): User
    {
        /** @var User $usuario */
        $usuario = $this->usuarioRepository->buscarParaEditar($id);

        return $usuario;
    }

    public function eliminar(int $id): bool
    {
        return $this->usuarioRepository->eliminar($id);
    }
}