<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\PrestamoTrait;
use App\Models\PagoCuota;
use Illuminate\Http\Request;
use App\Http\Resources\PagoCuotaResource;

class PagoCuotaController extends Controller
{
    use PrestamoTrait;

    public function getRecordatorios()
    {
        // Definimos la fecha de hoy y la fecha de hoy con 5 dias de adelanto
        $today = Carbon::now()->toDateString();
        $fiveDaysLater = Carbon::now()->addDays(5)->toDateString();

        // Obtenemos las cuotas pendientes con fecha de pago entre hace 5 días y hoy
        $cuotasPendientes = PagoCuota::with(['prestamo.cliente'])
                                    ->whereBetween('fecha_pago', [$today, $fiveDaysLater])
                                    ->where('pagado', 0)
                                    ->where('recordado', 0)
                                    ->get();

        return PagoCuotaResource::collection($cuotasPendientes);
    }
    /**
     * Handle the incoming request.
     */
    public function pagarCuota(Request $request)
    {
        $prestamo_id = $request->get('prestamo_id');
        $cuota_id = $request->get('cuota_id');
        /*
            La negación se usa para verificar la condición opuesta. Si el método checkIfPagoCuotaidBelongsToPrestamoid
            devuelve false, significa que la cuota no pertenece al préstamo. La negación convierte esto en true,
            lo que activa el bloque if
        */
        if(!$this->checkIfPagoCuotaidBelongsToPrestamoid($prestamo_id, $cuota_id)) {
            return $this->generateErrorResponse('La cuota no pertenece al prestamo', 422);
        }
        //obtenemos cuota
        /*
        El método first() obtiene el primer registro que coincide con las condiciones de la consulta
        y devuelve una instancia del modelo (o null si no se encuentra ningún registro).
        Esto permite acceder directamente a las propiedades y métodos del modelo.
        */
        $cuota = PagoCuota::where('id', $cuota_id)->first();
        $cuota->pagado = 1;
        $cuota->save();

        return response()->json([
            'message' => 'Cuota Pagada'
        ]);
    }
}
