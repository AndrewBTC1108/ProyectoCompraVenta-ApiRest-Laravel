<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\PagoCuota;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StorePrestamoRequest;
use App\Http\Requests\UpdatePrestamoRequest;
use App\Http\Resources\PrestamoResource;
use App\PrestamoTrait;

class PrestamoController extends Controller
{
    use PrestamoTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cliente_id = $request->get('cliente_id');
        $pagePActive = $request->get('pagePActive', 1); // pagePActive por defecto es 1
        $pagePPrevious = $request->get('pagePPrevious', 1); // pagePPrevious por defecto es 1
        // Obtener los préstamos activos paginados
        $prestamosActivos = Prestamo::where('cliente_id', $cliente_id)
            ->where('disponible', 1)
            ->select(['id', 'fecha', 'valor_prestado', 'cuotas', 'porcentaje', 'total', 'producto_id'])
            ->with(['producto'])
            ->paginate(6, ['*'], 'pagePActive', $pagePActive);

        // Obtener los préstamos anteriores paginados
        $prestamosAnteriores = Prestamo::where('cliente_id', $cliente_id)
            ->where('disponible', 0)
            ->select(['id', 'fecha', 'valor_prestado', 'cuotas', 'porcentaje', 'total', 'producto_id'])
            ->with(['producto'])
            ->paginate(6, ['*'], 'pagePPrevious', $pagePPrevious);

        return response()->json([
            'prestamosActivos' => $prestamosActivos,
            'prestamosAnteriores' => $prestamosAnteriores
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : JsonResponse
    {
        $prestamo = Prestamo::findOrFail($id);
        //El método get() devuelve una colección de resultados, incluso si solo hay un resultado.
        $prestamoCuotas = $prestamo->pagosCuotas()
            ->get(['id', 'numero_cuota', 'fecha_pago', 'monto_pago', 'pagado']);

        return response()->json($prestamoCuotas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrestamoRequest $request) : JsonResponse
    {
        //validar
        $data = $request->validated();
        //validar que el producto no pertenezca el cliente
        if (!$this->checkIfProductBelongsToCliente($data['producto_id'], $data['cliente_id'])) {
            return $this->generateErrorResponse('El producto no pertenece al cliente.', 422);
        }
        //validar que haya un pestamo ya con ese producto y cliente
        if ($this->PendingPrestamo($data['producto_id'], $data['cliente_id'])) {
            return $this->generateErrorResponse('Ya hay un prestamo pendiente con el producto seleccionado.', 422);
        }
        //aplicar porcentaje del 7% al dinero
        $total = ($data['valor_prestado'] * $data['porcentaje'] / 100) + $data['valor_prestado'];
        //redondear decimales
        $valorCuota = round($total / $data['cuotas'], 2);

        //crearPrestamo
        $prestamo = Prestamo::create([
            'fecha' => $data['fecha'],
            'valor_prestado' => $data['valor_prestado'],
            'cuotas' => $data['cuotas'],
            'porcentaje' => $data['porcentaje'],
            'total' => $total,
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
    public function update(UpdatePrestamoRequest $request, string $id)
    {
        // Validar los datos entrantes
        $data = $request->validated();

        //validar que el producto no pertenezca el cliente
        if (!$this->checkIfProductBelongsToCliente($data['producto_id'], $data['cliente_id'])) {
            return $this->generateErrorResponse('El producto no pertenece al cliente.', 422);
        }

        // Encontrar el préstamo existente
        $prestamo = Prestamo::findOrFail($id);

        // Aplicar el porcentaje y calcular el nuevo total y valor de cuota
        $total = ($data['valor_prestado'] * $data['porcentaje'] / 100) + $data['valor_prestado'];
        $valorCuota = round($total / $data['cuotas'], 2);

        // Actualizar el préstamo con los nuevos datos
        $prestamo->update([
            'fecha' => $data['fecha'],
            'valor_prestado' => $data['valor_prestado'],
            'cuotas' => $data['cuotas'],
            'porcentaje' => $data['porcentaje'],
            'total' => $total,
            'producto_id' => $data['producto_id'],
            'cliente_id' => $data['cliente_id'],
        ]);

        // Eliminar las cuotas anteriores
        PagoCuota::where('prestamo_id', $prestamo->id)->delete();

        // Calcular y crear las nuevas cuotas
        $fechaPago = new \DateTime($data['fecha']);
        for ($i = 1; $i <= $data['cuotas']; $i++) {
            $fechaPago->modify('+1 month');
            PagoCuota::create([
                'prestamo_id' => $prestamo->id,
                'numero_cuota' => $i,
                'fecha_pago' => $fechaPago->format('Y-m-d'),
                'monto_pago' => $valorCuota,
                'pagado' => 0,
            ]);
        }

        return response()->json([
            'message' => 'Prestamo actualizado correctamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) :JsonResponse
    {
        //encontrar prestamo
        $prestamo = Prestamo::findOrFail($id);

        $prestamo->delete();

        return response()->json([
            'message' => 'Prestamo eliminado'
        ]);
    }
}
