<?php

namespace App\Http\Controllers\Auth;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class LoginController extends Controller
{
    private $mensaje = [];
    public function showLogin()
    {
        return view('auth.login');
    }
    public function ingresar(Request $request)
    {
        try {

            $credentials = $request->validate([
                'usuario' => ['required', 'string'],
                'password' => ['required'],
            ]);
            $credentials['estado'] = 'activo';
            if (!Auth::attempt($credentials, $request->boolean('remember'))) {
                throw new Exception('Credenciales incorrectas.');
            }

            $request->session()->regenerate();

            $this->mensaje('success', 'Iniciando correctamente...');
            return response()->json($this->mensaje, 200);

        } catch (ValidationException $e) {

            return response()->json([
                'tipo' => 'error',
                'mensaje' => 'Todos los campos son requeridos...',
            ], 200);

        } catch (\Exception $e) {

            $this->mensaje('error', $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function salir(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['tipo' => 'success', 'mensaje' => 'Cerrando sesion...']);
    }

    public function mensaje($titulo, $mensaje)
    {
        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje,
        ];
    }
}
