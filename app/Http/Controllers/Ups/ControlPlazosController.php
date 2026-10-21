<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ControlPlazosController extends Controller
{
    public function index(): View
    {
        $memoriaVencida = Proyecto::query()->with(['areaConocimiento', 'estudiantes'])
            ->whereDate('fecha_inicio', '<=', now()->subMonths(3)->toDateString())
            ->whereDoesntHave('memorias')->orderBy('fecha_inicio')->paginate(10, ['*'], 'memoria_page');
        $junta = Proyecto::query()->with(['areaConocimiento', 'estudiantes', 'creador'])
            ->whereDate('fecha_inicio', '<=', now()->subMonths(18)->toDateString())
            ->orderBy('fecha_inicio')->paginate(10, ['*'], 'junta_page');

        return view('ups.plazos.index', compact('memoriaVencida', 'junta'));
    }

    public function remitirJunta(Request $request, string $proyectoId): RedirectResponse
    {
        $proyecto = DB::transaction(function () use ($proyectoId): Proyecto {
            $proyecto = Proyecto::query()->lockForUpdate()->findOrFail($proyectoId);
            abort_if($proyecto->fecha_inicio->gt(now()->subMonths(18)->startOfDay()), 422, 'El proyecto todavía no cumple 18 meses.');
            abort_if($proyecto->fecha_remision_junta, 409, 'Este proyecto ya fue remitido a Junta Directiva.');
            $proyecto->update(['fecha_remision_junta' => now(), 'remitido_junta_por' => auth()->id()]);

            return $proyecto;
        });

        return redirect()->route('ups.plazos.remision', $proyecto->id_proyecto)->with('success', 'Remisión formal generada.');
    }

    public function remision(string $proyectoId): View
    {
        return view('ups.plazos.remision', [
            'proyecto' => Proyecto::query()->with(['areaConocimiento', 'estudiantes', 'remitenteJunta'])->findOrFail($proyectoId),
        ]);
    }
}
