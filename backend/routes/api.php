<?php

use App\Http\Controllers\HealthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CamisetaController;
use App\Http\Controllers\TallaController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

/*
|--------------------------------------------------------------------------
| Clientes
|--------------------------------------------------------------------------
*/
Route::apiResource('clientes', ClienteController::class);

/*
|--------------------------------------------------------------------------
| Listar camisetas compradas por un cliente
|--------------------------------------------------------------------------
*/
Route::get(
    'clientes/{cliente}/camisetas',
    [ClienteController::class, 'camisetas']
);

/*
|--------------------------------------------------------------------------
| Camisetas
|--------------------------------------------------------------------------
*/
Route::apiResource('camisetas', CamisetaController::class);
Route::get('camisetas/{camiseta}/precio/{cliente}', [CamisetaController::class, 'precio']);

/*
|--------------------------------------------------------------------------
| Tallas
|--------------------------------------------------------------------------
*/
Route::apiResource('tallas', TallaController::class);

/*
|--------------------------------------------------------------------------
| Ventas
|--------------------------------------------------------------------------
*/
Route::apiResource('ventas', VentaController::class);
