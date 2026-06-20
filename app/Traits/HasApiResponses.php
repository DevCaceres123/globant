<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
trait HasApiResponses
{

    protected function success(string $mensaje, mixed $datos = null, int $status = 200): JsonResponse
    {
        $response = [
            'tipo' => 'exito',
            'mensaje' => $mensaje,
        ];

        if (!is_null($datos)) {
            $response['datos'] = $datos;
        }

        return response()->json($response, $status);
    }

    protected function notFound(string $mensaje): JsonResponse
    {
        return $this->error($mensaje, 404);
    }

    protected function validationError(MessageBag $errores): JsonResponse
    {
        return response()->json([
            'tipo' => 'errores',
            'mensaje' => $errores,
        ], 422);
    }

    protected function error(string $mensaje, int $status = 500): JsonResponse
    {
        return response()->json([
            'tipo' => 'error',
            'mensaje' => $mensaje,
        ], $status);
    }
}