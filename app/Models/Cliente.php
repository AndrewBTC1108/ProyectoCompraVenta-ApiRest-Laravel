<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'telefono',
        'direccion_residencia'
    ];

    //relaciones de uno muchos
    public function prestamos() : HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    public function productos() : HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
