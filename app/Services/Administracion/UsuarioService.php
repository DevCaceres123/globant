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
        if ($id === auth()->id()) {
            throw new \Exception('No puedes eliminar tu propio usuario.');
        }

        return $this->usuarioRepository->eliminar($id);
    }

    public function obtenerRoles()
    {
        return $this->usuarioRepository->obtenerRoles();
    }

    public function obtenerTotalUsuarios(): int
    {
        return $this->usuarioRepository->obtenerTotal();
    }


    public function obtenerTotalUsuariosActivos(): int
    {
        return $this->usuarioRepository->obtenerTotalActivos();
    } 

    public function obtenerTotalUsuariosInactivos(): int
    {
        return $this->usuarioRepository->obtenerTotalInactivos();
    }

    public function obtenerTotalRoles(): int
    {
        return $this->usuarioRepository->obtenerTotalRoles();
    }
}