<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormatoResource;
use App\Models\Plantilla;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormatoApiController extends Controller
{
    /**
     * Catálogo público de formatos y plantillas oficiales vigentes (F1 a F8).
     * Permite a subsistemas externos (SYS-02 y SYS-03) consultar los formatos oficiales.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $formatos = Plantilla::query()
            ->where('activa', true)
            ->whereHas('versionPublicada', fn ($q) => $q->whereNotNull('ruta_archivo'))
            ->with('versionPublicada')
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($q) => $q
                ->where('nombre', 'ilike', "%{$search}%")
                ->orWhere('descripcion', 'ilike', "%{$search}%")))
            ->when($request->query('tipo'), fn ($query, $tipo) => $query->whereHas('versionPublicada', fn ($q) => $q->where('tipo', $tipo)))
            ->orderBy('nombre')
            ->paginate($request->integer('per_page', 25));

        return FormatoResource::collection($formatos);
    }

    /**
     * Detalle de un formato oficial vigente por ID.
     */
    public function show(string $id): JsonResponse
    {
        $plantilla = Plantilla::query()
            ->where('activa', true)
            ->with('versionPublicada')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new FormatoResource($plantilla),
        ]);
    }

    /**
     * Descarga pública del archivo binario correspondiente a la versión oficial vigente (HU-43).
     */
    public function descargar(string $id): StreamedResponse|JsonResponse
    {
        $plantilla = Plantilla::query()
            ->where('activa', true)
            ->with('versionPublicada')
            ->find($id);

        if (! $plantilla || ! $plantilla->versionPublicada) {
            return response()->json([
                'status' => 'error',
                'message' => 'El formato oficial solicitado no existe o no cuenta con una versión vigente publicada.',
            ], 404);
        }

        $version = $plantilla->versionPublicada;

        if (! $version->ruta_archivo || ! Storage::disk('public')->exists($version->ruta_archivo)) {
            return response()->json([
                'status' => 'error',
                'message' => 'El archivo físico del formato oficial no se encuentra disponible en el almacenamiento institucional.',
            ], 404);
        }

        return Storage::disk('public')->download($version->ruta_archivo, $version->nombre_archivo);
    }
}
