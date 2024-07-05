<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


/**
 * @group Auth
 */
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validación de los datos recibidos en la solicitud
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Buscar al usuario en la base de datos mediante el email proporcionado
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe o si la contraseña proporcionada es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Si el usuario no existe o la contraseña es incorrecta, se lanza una excepción de validación
            // que será manejada por Laravel y devolverá un error 422 (Unprocessable Entity).
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Obtener el User Agent del navegador y limitar su longitud a 255 caracteres.
        $device    = substr($request->userAgent() ?? '', 0, 255);

        // Establecer la fecha de vencimiento del token de acceso.
        // Si se seleccionó "recordar sesión", el token no tendrá fecha de vencimiento, de lo contrario,
        // tendrá una duración de 60 minutos desde la generación.
        $expiresAt = $request->remember ? null : now()->addMinutes(60);

        // Generar un nuevo token de acceso para el usuario y devolverlo como respuesta en formato JSON.
        return response()->json([
            'access_token' => $user->createToken($device, ['expires_at' => $expiresAt])->plainTextToken,
        ], Response::HTTP_CREATED);
    }
}