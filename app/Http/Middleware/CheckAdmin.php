<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado y es administrador
        if (Auth::check() && Auth::user()->es_admin) {
            return $next($request);
        }

        // El usuario no es administrador, redirigir o devolver una respuesta de error
        return response()->json(['error' => 'Acceso no autorizado.'], 403);
    }
}
