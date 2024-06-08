<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrestamoController;

Route::middleware(['auth:sanctum'])->group( function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [LoginController::class, 'destroy']);

    Route::get('/categorias', [CategoriasController::class, 'index']);

    Route::apiResource('/clientes', ClienteController::class);

    Route::apiResource('/prestamos', PrestamoController::class);
});

Route::middleware('guest')->group(function() {

    Route::post('/login', [LoginController::class, 'store'])
                ->name('login');
});
