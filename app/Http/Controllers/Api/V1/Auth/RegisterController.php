<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\RegisterRequest;
use OpenApi\Annotations as OA;


class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "document_number", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Juan Perez"),
     *             @OA\Property(property="document_number", type="string", example="12345678"),
     *             @OA\Property(property="email", type="string", format="email", example="juan.perez@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="securePassword123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Juan Perez"),
     *                 @OA\Property(property="document_number", type="string", example="12345678"),
     *                 @OA\Property(property="email", type="string", format="email", example="juan.perez@example.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error en el servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al registrar al usuario: mensaje de error específico")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        try {
            //Create user
            $user = User::create([
                'name' => $request->input('name'),
                'document_number' => $request->input('document_number'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            // Crear un token de acceso para el usuario utilizando Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

             // Devolver una respuesta con el token de acceso y el usuario creado
            return response()->json([ 'user' => $user, 'token' => $token, 'token_type' => 'Bearer', ], 201);

        } catch (\Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return response()->json(['message' => 'Error al registrar al usuario: ' . $e->getMessage()], 500);
        }
    }
}