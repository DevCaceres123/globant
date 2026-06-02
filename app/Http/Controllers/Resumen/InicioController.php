<?php

namespace App\Http\Controllers\Resumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        // if (!auth()->user()->can('sede.inicio')) {
        //     return redirect()->route('inicio');
        // }
        return view('administrador.inicio');
    }
}
