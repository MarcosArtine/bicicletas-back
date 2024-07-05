<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @group Auth
 */
class RegistroController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validar los datos enviados en la solicitud del cliente
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Crear un nuevo usuario con los datos proporcionados en la solicitud
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Obtener el dispositivo desde el cual se realizó la solicitud
        $device = substr($request->userAgent() ?? '', 0, 255);

        // Generar un token de acceso para el nuevo usuario
        $accessToken = $user->createToken($device)->plainTextToken;

        // Devolver una respuesta JSON con el token de acceso y código de estado HTTP 201 (CREATED)
        return response()->json([
            'access_token' => $accessToken,
        ], Response::HTTP_CREATED);
    }
}