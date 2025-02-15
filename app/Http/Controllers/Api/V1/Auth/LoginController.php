<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginRequest;
use OpenApi\Annotations as OA;

class LoginController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Iniciar sesión de usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Usuario Ejemplo"),
     *                 @OA\Property(property="email", type="string", format="email", example="usuario@example.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Contraseña o correo invalido"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error en el servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Algo salió mal"),
     *             @OA\Property(property="error", type="string", example="Mensaje de error específico")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
             // Busca el usuario en la base de datos a través del correo electrónico
            $user = User::where('email', '=', $request->email)->firstOrFail();

            // Comprueba si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('user_token')->plainTextToken;

                // Crea un nuevo token para el usuario y lo devuelve como respuesta en formato JSON
                return response()->json([ 'user' => $user, 'token' => $token ], 200);
            }

            // Si las credenciales no son válidas, devuelve un mensaje de error en formato JSON
            return response()->json([ 'error' => 'Contraseña o correo invalido' , 'success' => false ]);

        } catch (\Exception $e) {
            // Si ocurre una excepción, devuelve un mensaje de error en formato JSON
            return response()->json([ 'message' => 'Algo salió mal', 'error' => $e->getMessage() ]);
        }
    }

}