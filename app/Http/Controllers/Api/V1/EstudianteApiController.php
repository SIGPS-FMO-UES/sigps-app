<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EstudianteResource;
use App\Models\Estudiante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EstudianteApiController extends Controller
{
    /**
     * Listado paginado de estudiantes con filtros avanzados.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $estudiantes = Estudiante::query()
            ->with(['carreras'])
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($q) => $q
                ->where('due', 'ilike', "%{$search}%")
                ->orWhere('nombre_completo', 'ilike', "%{$search}%")))
            ->when($request->query('carrera_id'), fn ($query, $carreraId) => $query->whereHas('carreras', fn ($c) => $c->where('carreras.id_carrera', $carreraId)))
            ->latest('created_at')
            ->paginate($request->integer('per_page', 15));

        return EstudianteResource::collection($estudiantes);
    }

    /**
     * Ficha detallada de un estudiante por ID o carnet (DUE).
     */
    public function show(string $identificador): JsonResponse
    {
        $estudiante = Estudiante::query()
            ->with(['carreras', 'proyectos.areaConocimiento'])
            ->where('id_estudiante', $identificador)
            ->orWhere('due', strtoupper($identificador))
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => new EstudianteResource($estudiante),
        ]);
    }
}
