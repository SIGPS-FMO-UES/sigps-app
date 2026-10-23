<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AreaConocimientoController extends Controller
{
    public function index(): View
    {
        return view('admin.areas.index', [
            'areas' => AreaConocimiento::query()->withCount('proyectos')->orderBy('nombre')->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['nombre' => ['required', 'string', 'max:150'], 'detalles' => ['nullable', 'string', 'max:1000']]);
        AreaConocimiento::create($data + ['activa' => true]);

        return back()->with('success', 'Área de conocimiento creada.');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'detalles' => ['nullable', 'string', 'max:1000'],
            'alcance' => ['required', 'in:retroactivo,futuro'],
        ]);

        DB::transaction(function () use ($id, $data): void {
            $area = AreaConocimiento::query()->lockForUpdate()->findOrFail($id);
            if ($data['alcance'] === 'futuro') {
                $area->update(['activa' => false]);
                AreaConocimiento::create(['nombre' => $data['nombre'], 'detalles' => $data['detalles'], 'activa' => true]);
            } else {
                $area->update(['nombre' => $data['nombre'], 'detalles' => $data['detalles']]);
            }
        });

        return back()->with('success', 'Área actualizada según el alcance seleccionado.');
    }

    public function cambiarEstado(Request $request, string $id): RedirectResponse
    {
        $area = AreaConocimiento::query()->findOrFail($id);
        $area->update(['activa' => ! $area->activa]);

        return back()->with('success', 'Disponibilidad del área actualizada.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $area = AreaConocimiento::query()->withCount('proyectos')->findOrFail($id);
        if ($area->proyectos_count > 0) {
            return back()->withErrors(['area' => 'No se puede eliminar: hay proyectos vinculados a esta área. Puedes desactivarla para que no aparezca en nuevos proyectos.']);
        }

        $area->delete();

        return to_route('admin.areas.index')->with('success', 'Área de conocimiento eliminada correctamente.');
    }
}
