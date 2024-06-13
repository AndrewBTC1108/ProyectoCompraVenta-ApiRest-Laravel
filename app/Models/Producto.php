<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo',
        'observaciones',
        'cliente_id'
    ];

    //relacion de pertenencia
    public function cliente()
    {
        return $this->belongsTo(User::class);
    }
}
