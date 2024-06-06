<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriaCollection;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    public function index() : CategoriaCollection
    {
        return new CategoriaCollection(Categoria::all());
    }
}
