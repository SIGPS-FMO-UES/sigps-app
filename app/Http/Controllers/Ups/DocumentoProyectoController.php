<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use App\Models\DocumentoProyecto;
use App\Models\LogAccion;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentoProyectoController extends Controller
{
    public function index(string $id): View
    {
        $proyecto = Proyecto::query()
            ->with([
                'documentos' => fn ($query) => $query->latest(),
                'documentos.usuario',
                'memoria',
                'areaConocimiento',
                'estudiantes',
            ])
            ->findOrFail($id);

        return view('ups.proyectos.expediente', compact('proyecto'));
    }

    public function store(Request $request, string $id): RedirectResponse
    {
        $proyecto = Proyecto::query()->findOrFail($id);
        abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'No se pueden agregar documentos a un proyecto cerrado.');
        $datos = $request->validate([
            'fase' => ['required', 'in:inscripcion,anteproyecto,avance_50,memoria_final,otro'],
            'archivo' => ['required', 'file', 'mimes:pdf', 'max:15360'],
        ]);
        $archivo = $datos['archivo'];
        $ruta = $archivo->store("proyectos/{$proyecto->id_proyecto}", 'expedientes_ups');

        DB::transaction(function () use ($proyecto, $datos, $archivo, $ruta): void {
            $documento = $proyecto->documentos()->create([
                'fase' => $datos['fase'],
                'nombre_original' => $archivo->getClientOriginalName(),
                'ruta' => $ruta,
                'mime_type' => 'application/pdf',
                'tamano_bytes' => $archivo->getSize(),
                'usuario_id' => auth()->id(),
            ]);
            LogAccion::create([
                'accion' => "Documento {$documento->nombre_original} agregado al proyecto #{$proyecto->id_proyecto}",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'El documento quedó guardado en el expediente privado.');
    }

    public function descargar(string $id): BinaryFileResponse
    {
        $documento = DocumentoProyecto::query()->findOrFail($id);
        $ruta = $documento->ruta;
        abort_unless(is_string($ruta) && $ruta !== '' && ! str_contains($ruta, '..') && ! str_contains($ruta, '\\')
            && ! str_starts_with($ruta, '/') && Storage::disk('expedientes_ups')->exists($ruta), 404);

        return response()->file(Storage::disk('expedientes_ups')->path($ruta), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
