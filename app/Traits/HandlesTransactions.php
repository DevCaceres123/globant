<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

trait HandlesTransactions
{
    protected function transaction(callable $callback)
    {
        try {

            DB::beginTransaction();

            $resultado = $callback();

            DB::commit();

            return $resultado;

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Error en transacción', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'usuario_id' => auth()->id(),
                'ip' => request()->ip(),
                'url' => request()->fullUrl(),
            ]);

            throw $e;
        }
    }
}