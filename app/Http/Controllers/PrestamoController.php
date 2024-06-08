<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\PagoCuota;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StorePrestamoRequest;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrestamoRequest $request) : JsonResponse
    {
        //validar
        $data = $request->validated();
        //aplicar porcentaje del 7% al dinero
        $total = ($data['valor_prestado'] * $data['porcentaje'] / 100) + $data['valor_prestado'];
        //redondear decimales
        $valorCuota = round($total / $data['cuotas'], 2);

        //crearPrestamo
        $prestamo = Prestamo::create([
            'fecha' => $data['fecha'],
            'valor_prestado' => $total,
            'cuotas' => $data['cuotas'],
            'porcentaje' => $data['porcentaje'],
            'producto_id' => $data['producto_id'],
            'cliente_id' => $data['cliente_id'],
        ]);

        // Calcular y crear los pagos de cuotas
        $fechaPago = new \DateTime($data['fecha']); // Convertir la fecha a un objeto DateTime
        for ($i = 1; $i <= $data['cuotas']; $i++) {
            // Añadir el número de meses para calcular la fecha de pago
            $fechaPago->modify('+1 month');

            // Crear el pago de cuota
            PagoCuota::create([
                'prestamo_id' => $prestamo->id,
                'numero_cuota' => $i,
                'fecha_pago' => $fechaPago->format('Y-m-d'), // Formatear la fecha como string 'YYYY-MM-DD'
                'monto_pago' => $valorCuota,
                'pagado' => 0, // Inicialmente, la cuota no está pagada
            ]);
        }

        return response()->json([
            'message' => 'Prestamo Realizado'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
