<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LogoutRequest;
use OpenApi\Annotations as OA;


class LogoutController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Cerrar sesión de usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Sesión cerrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sesión cerrada exitosamente"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado"),
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
    public function logout(LogoutRequest $request)
    {
        try {
            // Obtener el usuario autenticado
            $user = User::findOrFail($request->user_id);

            // Revocar todos los tokens de acceso del usuario
            $user->tokens()->delete();

            // Devolver una respuesta exitosa
            return response()->json([ 'message' => 'Seccion cerrada exitosomente', 'success' => true,], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Algo salió mal', 'error' => $e->getMessage()]);
        }
    }

}