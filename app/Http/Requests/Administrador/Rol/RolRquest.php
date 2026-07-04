<?php

namespace App\Http\Requests\Administrador\Rol;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Http\Requests\BasePrincipalRequest;

class RolRquest extends BasePrincipalRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeName = $this->route()->getName();

        switch ($routeName) {
            case 'rol.store':
                return [
                    'name' => 'required|string|max:40|min:3',
                    'descripcion' => 'required|string|max:100|min:3',
                    'color' => 'required',
                    'permisos' => 'nullable|array',
                    'permisos.*' => 'exists:permissions,name',

                ];
            case 'rol.update':                
                return [
                    'name' => 'required|string|max:40|min:3',
                    'descripcion' => 'required|string|max:100|min:3',
                    'color' => 'required',
                    'permisos' => 'nullable|array',
                    'permisos.*' => 'exists:permissions,name',

                ];
            default:
                return [];
        }
    }

}
