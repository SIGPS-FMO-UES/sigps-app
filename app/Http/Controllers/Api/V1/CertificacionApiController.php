<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificacionResource;
use App\Models\Certificacion;
use App\Models\LogAccion;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CertificacionApiController extends Controller
{
    /**
     * Listado paginado de certificaciones oficiales.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $certificaciones = Certificacion::query()
            ->with(['proyecto', 'estudiante', 'emisor'])
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($q) => $q
                ->where('folio', 'ilike', "%{$search}%")
                ->orWhereHas('estudiante', fn ($e) => $e->where('due', 'ilike', "%{$search}%")->orWhere('nombre_completo', 'ilike', "%{$search}%"))))
            ->when($request->query('entregada') !== null, function ($query) use ($request) {
                $entregada = filter_var($request->query('entregada'), FILTER_VALIDATE_BOOLEAN);
                $entregada ? $query->whereNotNull('fecha_entrega') : $query->whereNull('fecha_entrega');
            })
            ->latest('fecha_emision')
            ->paginate($request->integer('per_page', 15));

        return CertificacionResource::collection($certificaciones);
    }

    /**
     * Emisión oficial de certificación con número de folio inmutable (exclusivo Jefatura UPS).
     */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'proyecto_id' => ['required', 'integer', 'exists:proyectos,id_proyecto'],
            'estudiante_id' => ['required', 'integer', 'exists:estudiantes,id_estudiante'],
            'folio' => ['required', 'string', 'max:50', 'unique:certificaciones,folio'],
            'fecha_emision' => ['nullable', 'date'],
        ]);

        $certificacion = DB::transaction(function () use ($datos): Certificacion {
            $proyecto = Proyecto::query()->with('memoria')->findOrFail($datos['proyecto_id']);

            // Verificación de elegibilidad
            $participa = $proyecto->estudiantes()->where('estudiantes.id_estudiante', $datos['estudiante_id'])->exists();
            abort_unless($participa, 422, 'El estudiante no está asociado a este proyecto.');

            $cert = Certificacion::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'estudiante_id' => $datos['estudiante_id'],
                'folio' => $datos['folio'],
                'fecha_emision' => $datos['fecha_emision'] ?? now(),
                'emitida_por' => auth()->id() ?? 1,
            ]);

            LogAccion::create([
                'accion' => "API: Emisión de certificación folio #{$cert->folio} para estudiante #{$cert->estudiante_id}.",
                'usuario' => auth()->id() ?? 1,
            ]);

            return $cert;
        });

        $certificacion->load(['proyecto', 'estudiante', 'emisor']);

        return response()->json([
            'status' => 'success',
            'message' => 'Certificación oficial emitida exitosamente.',
            'data' => new CertificacionResource($certificacion),
        ], 201);
    }

    /**
     * Registro de la entrega física del certificado al estudiante.
     */
    public function marcarEntregada(Request $request, string $id): JsonResponse
    {
        $datos = $request->validate([
            'fecha_entrega' => ['required', 'date'],
            'firma_receptor' => ['nullable', 'string', 'max:255'],
        ]);

        $certificacion = Certificacion::query()->with(['proyecto', 'estudiante', 'emisor'])->findOrFail($id);

        $certificacion->update([
            'fecha_entrega' => $datos['fecha_entrega'],
            'firma_receptor' => $datos['firma_receptor'] ?? $certificacion->estudiante->nombre_completo,
            'entregada_por' => auth()->id() ?? 1,
        ]);

        LogAccion::create([
            'accion' => "API: Entrega física de certificación #{$certificacion->id_certificacion} (Folio: {$certificacion->folio}).",
            'usuario' => auth()->id() ?? 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Constancia de entrega física registrada exitosamente.',
            'data' => new CertificacionResource($certificacion),
        ]);
    }
}
