<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * @group Auth
 */
class PerfilController extends Controller
{
    /**
     * Muestra los detalles del perfil del usuario actual.
     *
     * @param  Request  $request
     * @return Response
     */
    public function show(Request $request)
    {
        // Obtener el usuario autenticado actualmente desde el objeto $request
        $user = $request->user();

        // Devolver una respuesta JSON con solo los campos 'name' y 'email' del usuario
        return response()->json($user->only('name', 'email'));
    }

    /**
     * Actualiza el perfil del usuario actual.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        // Validar los datos del formulario enviado por el usuario
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user())],
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        // Si los datos pasan la validación, actualizar el perfil del usuario
        $request->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->input('password')),
        ]);

        // Devolver una respuesta JSON con los datos validados y el código de estado HTTP 202 (Accepted)
        return response()->json($validatedData, Response::HTTP_ACCEPTED);
    }
}