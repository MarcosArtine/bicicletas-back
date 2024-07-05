<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group Auth
 */
class LogoutController extends Controller
{
    /**
     * Cerrar sesión del usuario y revocar el token de acceso actual.
     *
     * @param  Request  $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        // Obtener el token de acceso actual del usuario autenticado y eliminarlo
        $request->user()->currentAccessToken()->delete();

        // Devolver una respuesta HTTP con código 204 (No Content) para indicar éxito sin contenido
        return response()->noContent();
    }
}
