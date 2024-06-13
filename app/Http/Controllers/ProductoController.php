<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
        // Obtener los parámetros de la solicitud con valores por defecto
        $page = $request->get('page', 1); // Página por defecto es 1
        $search = $request->get('search', ''); // Término de búsqueda por defecto es una cadena vacía
        $cliente_id = $request->get('cliente_id'); // Obtener el cliente_id de la solicitud

        // Consulta de productos que coinciden con el término de búsqueda y el cliente_id, paginados
        $productos = Producto::where('cliente_id', $cliente_id)
            ->where(function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('tipo', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6, ['id', 'nombre', 'tipo', 'observaciones'], 'page', $page);

        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request) : JsonResponse
    {
        $data = $request->validated();

        Producto::create([
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'observaciones' => $data['observaciones'],
            'cliente_id' => $data['cliente_id']
        ]);

        return response()->json([
            'message' => 'Producto creado exitosamente'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, string $id) : JsonResponse
    {
        $producto = Producto::findOrFail($id);
        $data = $request->validated();
        $producto->update($data);
        //
        return response()->json([
            'message' => 'Producto Editado Correctamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : JsonResponse
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        //
        return response()->json([
            'message' => 'Producto Eliminado'
        ]);
    }
}
