<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function store(LoginRequest $request) : JsonResponse
    {
        // Intentar autenticar utilizando el método authenticate
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        // Resto del código para autenticar al usuario y devolver la respuesta
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Enviar el token en la respuesta
        return response()->json([
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //identificar que usuario esta haciendo el request
        $user = $request->user();
        //remover el token
        $user->currentAccessToken()->delete();
        return [
            'user' => null
        ];
    }
}
