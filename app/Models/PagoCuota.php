<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoCuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestamo_id',
        'numero_cuota',
        'fecha_pago',
        'monto_pago',
        'pagado'
    ];

    //relacion de pertenencia
    public function prestamo() : BelongsTo
    {
        return $this->belongsTo(Prestamo::class);
    }
}
