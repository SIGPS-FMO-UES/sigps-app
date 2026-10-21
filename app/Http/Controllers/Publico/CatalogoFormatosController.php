<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CatalogoFormatosController extends Controller
{
    public function index(): View
    {
        return view('publico.formatos.index', [
            'plantillas' => Plantilla::query()->where('activa', true)->whereHas('versionPublicada', fn ($q) => $q->whereNotNull('ruta_archivo'))
                ->with('versionPublicada')->orderBy('nombre')->get(),
        ]);
    }

    public function descargar(string $id): StreamedResponse
    {
        $plantilla = Plantilla::query()->where('activa', true)->with('versionPublicada')->findOrFail($id);
        $version = $plantilla->versionPublicada;
        abort_unless($version && $version->ruta_archivo && Storage::disk('public')->exists($version->ruta_archivo), 404);

        return Storage::disk('public')->download($version->ruta_archivo, $version->nombre_archivo);
    }
}
