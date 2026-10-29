<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCarreraServiceToken
{
    /**
     * Valida el token de servicio de la carrera para el punto de integración HU-72.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenEsperado = (string) config('sigps.carrera_service_token');

        $tokenRecibido = $request->bearerToken()
            ?: $request->header('X-Carrera-Service-Token')
            ?: $request->input('service_token');

        if (! $tokenRecibido || ! hash_equals($tokenEsperado, (string) $tokenRecibido)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token de servicio de carrera inválido o no autorizado.',
            ], 401);
        }

        return $next($request);
    }
}
