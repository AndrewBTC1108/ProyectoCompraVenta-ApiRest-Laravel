<?php

namespace App;

use App\Models\PagoCuota;
use App\Models\Prestamo;
use App\Models\Producto;

trait PrestamoTrait
{
    public function checkIfProductBelongsToCliente($producto_id, $cliente_id) : bool
    {
        return Producto::where('id', $producto_id)
            ->where('cliente_id', $cliente_id)
            ->exists();
    }

    public function PendingPrestamo($producto_id, $cliente_id) : bool
    {
        return Prestamo::where('producto_id', $producto_id)
            ->where('cliente_id', $cliente_id)
            ->where('disponible', 1)//verificar que este disponible tambien
            ->exists();
    }
    public function checkIfPagoCuotaidBelongsToPrestamoid($prestamo_id, $cuota_id) : bool
    {
        return PagoCuota::where('id', $cuota_id)
            ->where('prestamo_id', $prestamo_id)
            ->exists();
    }

    public function generateErrorResponse($message, $status)
    {
        return response()->json(['errors' => ['error' => [$message]]], $status);
    }
}
