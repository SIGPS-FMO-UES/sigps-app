<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProyectoResource;
use App\Models\HistorialFaseProyecto;
use App\Models\LogAccion;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProyectoApiController extends Controller
{
    /**
     * Listado paginado de proyectos con filtros de estado y fase.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $proyectos = Proyecto::query()
            ->with(['areaConocimiento', 'estudiantes', 'memoria'])
            ->withCount('documentos')
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($q) => $q
                ->where('titulo', 'ilike', "%{$search}%")
                ->when(is_numeric($search), fn ($numQ) => $numQ->orWhere('numero_proyecto', (int) $search))
                ->orWhere('tutor', 'ilike', "%{$search}%")
                ->orWhereHas('estudiantes', fn ($estudiantes) => $estudiantes->where('due', 'ilike', "%{$search}%")
                    ->orWhere('nombre_completo', 'ilike', "%{$search}%"))))
            ->when($request->query('fase'), fn ($query, $fase) => $query->where('fase_actual', $fase))
            ->when($request->query('estado'), fn ($query, $estado) => $query->where('estado_expediente', $estado))
            ->when($request->query('area_id'), fn ($query, $areaId) => $query->where('area_de_conocimiento', $areaId))
            ->latest('created_at')
            ->paginate($request->integer('per_page', 12));

        return ProyectoResource::collection($proyectos);
    }

    /**
     * Consulta detallada de un expediente de proyecto.
     */
    public function show(string $id): JsonResponse
    {
        $proyecto = Proyecto::query()
            ->with(['areaConocimiento', 'estudiantes', 'memoria', 'documentos', 'historialFases'])
            ->withCount('documentos')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new ProyectoResource($proyecto),
        ]);
    }

    /**
     * Registro institucional de un nuevo proyecto desde la oficina UPS.
     */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'numero_proyecto' => ['required', 'integer', 'min:1', 'unique:proyectos,numero_proyecto'],
            'titulo' => ['required', 'string', 'max:255'],
            'area_de_conocimiento' => ['required', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
            'tutor' => ['required', 'string', 'max:255'],
            'subcoordinador' => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_final' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estudiantes' => ['required', 'array', 'min:1'],
            'estudiantes.*' => ['integer', 'exists:estudiantes,id_estudiante'],
        ]);

        $proyecto = DB::transaction(function () use ($datos): Proyecto {
            $nuevoProyecto = Proyecto::create([
                'creado_por' => auth()->id() ?? 1,
                'numero_proyecto' => $datos['numero_proyecto'],
                'titulo' => $datos['titulo'],
                'area_de_conocimiento' => $datos['area_de_conocimiento'],
                'tutor' => $datos['tutor'],
                'subcoordinador' => $datos['subcoordinador'],
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_final' => $datos['fecha_final'] ?? null,
                'fase_actual' => 'inscripcion',
                'estado_expediente' => 'en_tramite',
                'fecha_radicado' => now(),
            ]);

            $nuevoProyecto->estudiantes()->sync($datos['estudiantes']);

            HistorialFaseProyecto::create([
                'proyecto_id' => $nuevoProyecto->id_proyecto,
                'fase' => 'inscripcion',
                'evento' => 'apertura_institucional',
                'observaciones' => "Apertura de expediente registrada en la oficina de Proyección Social (Proyecto #{$nuevoProyecto->numero_proyecto}).",
                'usuario_id' => auth()->id() ?? 1,
            ]);

            LogAccion::create([
                'accion' => "API: Apertura de proyecto #{$nuevoProyecto->numero_proyecto} (ID #{$nuevoProyecto->id_proyecto}) '{$nuevoProyecto->titulo}'.",
                'usuario' => auth()->id() ?? 1,
            ]);

            return $nuevoProyecto;
        });

        $proyecto->load(['areaConocimiento', 'estudiantes']);

        return response()->json([
            'status' => 'success',
            'message' => 'Proyecto registrado exitosamente.',
            'data' => new ProyectoResource($proyecto),
        ], 201);
    }

    /**
     * Actualización de datos permitidos del proyecto.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $proyecto = Proyecto::query()->findOrFail($id);

        $datos = $request->validate([
            'numero_proyecto' => ['sometimes', 'required', 'integer', 'min:1', Rule::unique('proyectos', 'numero_proyecto')->ignore($proyecto->id_proyecto, 'id_proyecto')],
            'titulo' => ['sometimes', 'required', 'string', 'max:255'],
            'tutor' => ['sometimes', 'required', 'string', 'max:255'],
            'subcoordinador' => ['sometimes', 'required', 'string', 'max:255'],
            'fecha_final' => ['nullable', 'date'],
            'area_de_conocimiento' => ['sometimes', 'required', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
        ]);

        $proyecto->update($datos);

        LogAccion::create([
            'accion' => "API: Modificación de proyecto #{$proyecto->numero_proyecto} (ID #{$proyecto->id_proyecto}).",
            'usuario' => auth()->id() ?? 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Proyecto actualizado exitosamente.',
            'data' => new ProyectoResource($proyecto->load(['areaConocimiento', 'estudiantes'])),
        ]);
    }

    /**
     * Cancelación institucional de un proyecto.
     */
    public function destroy(string $id): JsonResponse
    {
        $proyecto = Proyecto::query()->findOrFail($id);

        $proyecto->update(['estado_expediente' => 'cancelado']);
        $proyecto->delete();

        LogAccion::create([
            'accion' => "API: Cancelación de proyecto #{$id}.",
            'usuario' => auth()->id() ?? 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Proyecto cancelado y archivado.',
        ], 200);
    }
}
