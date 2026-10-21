<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TipoPlantilla;
use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PlantillaController extends Controller
{
    public function index(): View
    {
        return view('admin.plantillas.index', [
            'plantillas' => Plantilla::query()->with(['versiones' => fn ($q) => $q->latest('numero_version')])->withCount('versiones')->orderBy('nombre')->paginate(15),
            'tipos' => TipoPlantilla::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'tipo' => ['required', 'in:archivo_ejemplo,autorellenable'],
            'archivo' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:15360'],
        ]);

        DB::transaction(function () use ($request, $data): void {
            $plantilla = Plantilla::create(['nombre' => $data['nombre'], 'descripcion' => $data['descripcion'] ?? null, 'activa' => true]);
            $path = $request->file('archivo')->store('formatos-oficiales', 'public');
            $plantilla->versiones()->create([
                'numero_version' => 1,
                'tipo' => $data['tipo'],
                'nombre_archivo' => $request->file('archivo')->getClientOriginalName(),
                'ruta_archivo' => $path,
                'publicada' => true,
            ]);
        });

        return back()->with('success', 'Formato oficial publicado.');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'tipo' => ['nullable', 'in:archivo_ejemplo,autorellenable'],
            'archivo' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:15360'],
        ]);
        $plantilla = Plantilla::query()->findOrFail($id);
        $plantilla->update(['nombre' => $data['nombre'], 'descripcion' => $data['descripcion'] ?? null]);

        if ($request->hasFile('archivo')) {
            DB::transaction(function () use ($request, $data, $plantilla): void {
                $plantilla->versiones()->where('publicada', true)->update(['publicada' => false]);
                $version = ((int) $plantilla->versiones()->max('numero_version')) + 1;
                $file = $request->file('archivo');
                $plantilla->versiones()->create([
                    'numero_version' => $version,
                    'tipo' => $data['tipo'] ?? 'archivo_ejemplo',
                    'nombre_archivo' => $file->getClientOriginalName(),
                    'ruta_archivo' => $file->store('formatos-oficiales', 'public'),
                    'publicada' => true,
                ]);
            });
        }

        return back()->with('success', 'Plantilla actualizada.');
    }

    public function cambiarEstado(string $id): RedirectResponse
    {
        $plantilla = Plantilla::query()->findOrFail($id);
        $plantilla->update(['activa' => ! $plantilla->activa]);

        return back()->with('success', 'Disponibilidad pública actualizada.');
    }
}
