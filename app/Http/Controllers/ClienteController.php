<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get permite colocar valor por defecto
        $page = $request->get('page', 1); // get the request page, default is 1
        $search = $request->get('search', ''); // Obtiene el término de búsqueda de la solicitud, por defecto es ''

        // Obtiene los usuarios paginados que coinciden con el término de búsqueda y que no son administradores
        $clientes = Cliente::where(function ($query) use ($search) {
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('apellido', 'like', "%{$search}%")
                ->orWhere('cedula', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')
        ->paginate(6, ['id', 'cedula', 'nombre', 'apellido', 'telefono', 'direccion_residencia'], 'page', $page);

        // Retorna los usuarios como una respuesta JSON
        return response()->json($clientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request) : JsonResponse
    {
        //validar
        $data = $request->validated();

        //crearCliente
        Cliente::create([
            'cedula' => $data['cedula'],
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'telefono' => $data['telefono'],
            'direccion_residencia' => $data['direccion_residencia']
        ]);

        return response()->json([
            'message' => 'Cliente Registrado Exitosamente'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, string $id) : JsonResponse
    {
        $cliente = Cliente::findOrFail($id);
        $data = $request->validated();

        $cliente->update($data);

        return response()->json([
            'message' => 'Cliente Editado Correctamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : JsonResponse
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return response()->json([
            'message' => 'Cliente Eliminado'
        ]);
    }
}
