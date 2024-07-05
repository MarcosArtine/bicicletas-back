<?php

use App\Http\Controllers\Api\V1\AlquilerController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\PerfilController;
use App\Http\Controllers\Api\V1\Auth\RegistroController;
use App\Http\Controllers\Api\V1\BicicletaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->group(function () {
    Route::get('perfil', [PerfilController::class, 'show']);
    Route::put('perfil', [PerfilController::class, 'update']);
    Route::post('auth/logout', LogoutController::class);

    Route::post('alquileres/inicio', [AlquilerController::class, 'inicio']);
    Route::put('alquileres/finalizar/{alquiler}', [AlquilerController::class, 'finalizar']);
    Route::get('alquileres', [AlquilerController::class, 'index']);

    // Rutas que no requieren el middleware "es_admin"
    Route::apiResource('bicicletas', BicicletaController::class)->only(['index', 'show']);

    // Rutas que requieren el middleware "es_admin"
    Route::apiResource('bicicletas', BicicletaController::class)->except(['index', 'show'])->middleware('es_admin');
});

Route::post('auth/registro', RegistroController::class);
Route::post('auth/login', LoginController::class);
