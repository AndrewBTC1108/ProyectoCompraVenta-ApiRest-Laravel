<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\PagoCuotaController;
use App\Http\Controllers\ProductoPrestamoController;

Route::middleware(['auth:sanctum'])->group( function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/productosPrestamo', ProductoPrestamoController::class);

    Route::post('/pagoCuota', [PagoCuotaController::class, 'pagarCuota']);

    Route::get('/pagoCuota', [PagoCuotaController::class, 'getRecordatorios']);

    Route::post('/logout', [LoginController::class, 'destroy']);

    Route::get('/categorias', [CategoriasController::class, 'index']);

    Route::apiResource('/clientes', ClienteController::class);

    Route::apiResource('/prestamos', PrestamoController::class);

    Route::apiResource('/productos', ProductoController::class);
});

Route::middleware('guest')->group(function() {

    Route::post('/login', [LoginController::class, 'store'])
                ->name('login');
});
