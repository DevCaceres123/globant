<?php

namespace App\Http\Requests\Administrador\Usuario;

use App\Http\Requests\BasePrincipalRequest;

class UsuarioRequest extends BasePrincipalRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeName = $this->route()->getName();

        switch ($routeName) {
            case 'usuario.store':
                return [
                    'nombres'   => 'required|string|max:100|min:3',
                    'apellidos' => 'required|string|max:100|min:3',
                    'ci'        => 'required|string|max:30|unique:users,ci|min:3',
                    'email'     => 'required|email|max:255|unique:users,email',
                    'usuario'   => 'required|string|max:255|unique:users,usuario|min:3',
                    'password'  => 'required|string|min:6',
                    'rol'       => 'required|string|exists:roles,name',
                    'estado'    => 'required|in:activo,inactivo',
                ];
            default:
                return [];
        }
    }

    public function messages(): array
    {
        return [
            'nombres.required'   => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'ci.required'        => 'El carnet de identidad es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'El correo electrónico no es válido.',
            'email.unique'       => 'Este correo ya está registrado.',
            'usuario.required'   => 'El usuario es obligatorio.',
            'usuario.unique'     => 'Este nombre de usuario ya está en uso.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos :min caracteres.',
            'rol.required'       => 'Debe seleccionar un rol.',
            'rol.exists'         => 'El rol seleccionado no existe.',
            'estado.required'    => 'El estado es obligatorio.',
            'estado.in'          => 'El estado debe ser activo o inactivo.',
        ];
    }
}
