<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'valor_prestado',
        'cuotas',
        'porcentaje',
        'producto_id',
        'cliente_id'
    ];
    //relacion de uno a muchos
    public function pagosCuotas() : HasMany
    {
        return $this->hasMany(PagoCuota::class);
    }
    //relacion de pertenencia
    public function cliente() : BelongsTo
    {
        return $this->belongsTo(Cliente::class)->select(['id', 'cedula', 'nombre', 'apellido']);
    }

    public function producto() : BelongsTo
    {
        return $this->belongsTo(Producto::class)->select(['id', 'nombre', 'tipo']);
    }
}
