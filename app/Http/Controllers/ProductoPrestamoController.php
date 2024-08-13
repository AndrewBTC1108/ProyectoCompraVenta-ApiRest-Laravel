<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoPrestamoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cliente_id = $request->cliente_id;
        // Obtener productos que no están actualmente en préstamo (disponible = 1)
        $productosCliente = Producto::where('cliente_id', $cliente_id)
            ->whereNotIn('id', function ($query) {
                $query->select('producto_id')
                    ->from('prestamos')
                    ->where('disponible', 1); // Filtrar por productos disponibles, nos traera los que no tengan prestamos activos
            })
            ->get(['id', 'nombre']);

        return response()->json($productosCliente);
    }
}
