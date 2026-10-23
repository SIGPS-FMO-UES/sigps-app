<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarreraResource;
use App\Http\Resources\DepartamentoResource;
use App\Models\Carrera;
use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CarreraApiController extends Controller
{
    /**
     * Catálogo público de carreras disponibles con sus horas requeridas de servicio social y departamento.
     * Consumido por el Portal de Estudiantes (SYS-03) y Sistema de Coordinación (SYS-02).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $likeOp = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $departamentoId = $request->query('departamento_id') ?? $request->query('id_departamento');

        $query = Carrera::query()
            ->with('departamentoRel')
            ->when($departamentoId, fn ($q) => $q->where('departamento', $departamentoId))
            ->when($request->query('search'), fn ($q, $search) => $q->where(fn ($sub) => $sub
                ->where('nombre', $likeOp, "%{$search}%")
                ->orWhereHas('departamentoRel', fn ($dept) => $dept->where('nombre', $likeOp, "%{$search}%"))
            ))
            ->orderBy('nombre');

        $carreras = ($request->boolean('all') || ! $request->has('page'))
            ? $query->get()
            : $query->paginate($request->integer('per_page', 50));

        return CarreraResource::collection($carreras);
    }

    /**
     * Detalle público de una carrera específica por ID.
     */
    public function show(string $id): JsonResponse
    {
        $carrera = Carrera::query()
            ->with('departamentoRel')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new CarreraResource($carrera),
        ]);
    }

    /**
     * Catálogo académico unificado: lista estructurada de departamentos con carreras y listado plano de carreras.
     */
    public function catalogo(): JsonResponse
    {
        $departamentos = Departamento::query()
            ->with(['carreras' => fn ($q) => $q->orderBy('nombre')])
            ->orderBy('nombre')
            ->get();

        $carreras = Carrera::query()
            ->with('departamentoRel')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'departamentos' => DepartamentoResource::collection($departamentos),
                'carreras' => CarreraResource::collection($carreras),
            ],
        ]);
    }
}
