<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Autenticación API: Emisión de Bearer Token con Sanctum.
     */
    public function login(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'login' => ['required_without_all:email,nombre_usuario', 'string'],
            'email' => ['nullable', 'string'],
            'nombre_usuario' => ['nullable', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $loginInput = $datos['login'] ?? $datos['email'] ?? $datos['nombre_usuario'];
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'nombre_usuario';

        $user = User::query()->where($field, $loginInput)->first();

        if (! $user || ! Hash::check($datos['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Las credenciales proporcionadas no son válidas.'],
            ]);
        }

        if (! $user->activo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cuenta desactivada por la administración técnica.',
            ], 403);
        }

        $deviceName = $datos['device_name'] ?? 'UPS-API-Client';
        $permisos = $user->getAllPermissions()->pluck('name')->all();
        $token = $user->createToken($deviceName, $permisos)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token_type' => 'Bearer',
            'token' => $token,
            'user' => [
                'id_usuario' => $user->id_usuario,
                'nombre_usuario' => $user->nombre_usuario,
                'nombre_completo' => $user->nombre_completo,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->all(),
                'permisos' => $permisos,
            ],
        ]);
    }

    /**
     * Cierre de sesión y revocación del token actual.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Token revocado exitosamente.',
        ]);
    }

    /**
     * Consulta del usuario autenticado en la API.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id_usuario' => $user->id_usuario,
                'nombre_usuario' => $user->nombre_usuario,
                'nombre_completo' => $user->nombre_completo,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->all(),
                'permisos' => $user->getAllPermissions()->pluck('name')->all(),
            ],
        ]);
    }
}
