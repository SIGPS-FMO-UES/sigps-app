<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarreraResource;
use App\Http\Resources\DepartamentoResource;
use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class DepartamentoApiController extends Controller
{
    /**
     * Catálogo público de departamentos disponibles con sus carreras asociadas.
     * Consumido por el Portal de Estudiantes (SYS-03) y Sistema de Coordinación (SYS-02).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $likeOp = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

        $query = Departamento::query()
            ->with(['carreras' => fn ($q) => $q->orderBy('nombre')])
            ->withCount('carreras')
            ->when($request->query('search'), fn ($q, $search) => $q->where('nombre', $likeOp, "%{$search}%"))
            ->orderBy('nombre');

        $departamentos = ($request->boolean('all') || ! $request->has('page'))
            ? $query->get()
            : $query->paginate($request->integer('per_page', 50));

        return DepartamentoResource::collection($departamentos);
    }

    /**
     * Detalle público de un departamento específico por ID con sus carreras.
     */
    public function show(string $id): JsonResponse
    {
        $departamento = Departamento::query()
            ->with(['carreras' => fn ($q) => $q->orderBy('nombre')])
            ->withCount('carreras')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new DepartamentoResource($departamento),
        ]);
    }

    /**
     * Carreras asociadas a un departamento específico.
     */
    public function carreras(string $id): AnonymousResourceCollection
    {
        $departamento = Departamento::query()->findOrFail($id);

        $carreras = $departamento->carreras()
            ->with('departamentoRel')
            ->orderBy('nombre')
            ->get();

        return CarreraResource::collection($carreras);
    }
}
