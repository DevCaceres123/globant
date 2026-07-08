<?php

namespace App\Http\Controllers\Resumen;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class InicioController extends Controller
{
    public function index()
    {
        $totalUsuarios = User::count();
        $totalRoles = Role::count();
        return view('administrador.inicio', compact('totalUsuarios', 'totalRoles'));
    }
}
