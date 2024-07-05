<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlquilerResource;
use App\Models\Alquiler;
use App\Services\AlquilerPrecioService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Alquiler
 */
class AlquilerController extends Controller
{
    /**
     * Obtiene una lista paginada de todos los alquileres del usuario actual.
     */
    public function index(Request $request)
    {
        // Obtiene todos los alquileres asociados al usuario actual paginados.
        $alquileres = Alquiler::where('user_id', $request->user()->id)
            ->paginate();
        return AlquilerResource::collection($alquileres);
    }

    /**
     * Inicia un nuevo alquiler de bicicleta.
     */
    public function inicio(Request $request)
    {
        // Validación de campos del formulario de inicio de alquiler.
        $request->validate([
            "bicicleta_id" => ["required", "integer", "exists:bicicletas,id"],
        ]);

        // Verifica si la bicicleta está disponible para alquilar.
        if (Alquiler::where('bicicleta_id', $request->bicicleta_id)
            ->whereNull('hora_final')
            ->exists()
        ) {
            return response()->json([
                'errors' => ['general' => ['La bicicleta no se encuentra disponible']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Crea un nuevo registro de alquiler en la base de datos.
        $alquiler = Alquiler::create([
            "bicicleta_id" => $request->bicicleta_id,
            "hora_comienzo" => now(),
            "user_id" => $request->user()->id,
        ]);

        // Carga los datos de la bicicleta asociada al alquiler.
        $alquiler->load('bicicleta');

        // Retorna la información del alquiler en formato JSON.
        return AlquilerResource::make($alquiler);
    }

    /**
     * Finaliza un alquiler de bicicleta.
     */
    public function finalizar(Alquiler $alquiler, Request $request)
    {
        // Verifica si el alquiler ya ha sido finalizado previamente.
        if ($alquiler->hora_final !== null) {
            return response()->json([
                'errors' => ['general' => ['El alquiler ya ha sido finalizado']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Verifica si el alquiler pertenece al usuario actual.
        if ($alquiler->user->id !== $request->user()->id) {
            return response()->json([
                'errors' => ['general' => ['El alquiler no pertenece al usuario']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Calcula el precio total del alquiler utilizando el servicio AlquilerPrecioService.
        $precioTotal = AlquilerPrecioService::calcularPrecio($alquiler->hora_comienzo, $alquiler->bicicleta->precio_por_hora);

        // Actualiza los datos del alquiler con la hora final y el precio total.
        $alquiler->precio_total = $precioTotal;
        $alquiler->hora_final = now();
        $alquiler->save();

        // Retorna la información del alquiler actualizada en formato JSON.
        return AlquilerResource::make($alquiler);
    }
}
