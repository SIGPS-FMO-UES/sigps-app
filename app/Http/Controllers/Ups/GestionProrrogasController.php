<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use App\Models\HistorialFaseProyecto;
use App\Models\LogAccion;
use App\Models\ProcesoExtraordinario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GestionProrrogasController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'estado' => ['nullable', 'in:pendiente,aprobada,rechazada,registrado'],
            'tipo' => ['nullable', 'in:ampliacion_plazo,prorroga'],
        ]);
        $procesos = ProcesoExtraordinario::query()->with(['proyecto', 'creador', 'resolutor'])
            ->whereIn('tipo', ['ampliacion_plazo', 'prorroga'])
            ->when($filtros['estado'] ?? null, fn ($query, $estado) => $query->where('estado', $estado))
            ->when($filtros['tipo'] ?? null, fn ($query, $tipo) => $query->where('tipo', $tipo))
            ->latest()->paginate(15)->withQueryString();

        return view('ups.prorrogas.index', compact('procesos', 'filtros'));
    }

    public function resolver(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->can('prorrogas.resolver'), 403);
        $datos = $request->validate([
            'decision' => ['required', 'in:aprobada,rechazada'],
            'resolucion' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        DB::transaction(function () use ($id, $datos): void {
            $proceso = ProcesoExtraordinario::query()->lockForUpdate()->findOrFail($id);
            abort_unless($proceso->tipo === 'ampliacion_plazo' && $proceso->estado === 'pendiente', 409, 'Esta solicitud ya fue resuelta o no admite dictamen.');
            $proceso->update([
                'estado' => $datos['decision'],
                'resolucion' => $datos['resolucion'],
                'resuelto_por' => auth()->id(),
                'resuelto_at' => now(),
            ]);

            if ($datos['decision'] === 'aprobada') {
                $proyecto = $proceso->proyecto()->lockForUpdate()->firstOrFail();
                abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El proyecto fue cerrado mientras la solicitud estaba pendiente.');
                $proyecto->update(['fecha_final' => $proceso->fecha_nueva]);
            }

            HistorialFaseProyecto::create([
                'proyecto_id' => $proceso->proyecto_id,
                'fase' => $proceso->proyecto->fase_actual,
                'evento' => $datos['decision'] === 'aprobada' ? 'ampliacion_aprobada' : 'ampliacion_rechazada',
                'observaciones' => $datos['resolucion'],
                'usuario_id' => auth()->id(),
            ]);
            LogAccion::create(['accion' => "Solicitud de ampliación #{$proceso->id_proceso_extraordinario} {$datos['decision']}", 'usuario' => auth()->id()]);
        });

        return back()->with('success', 'La resolución quedó registrada en el historial del proyecto.');
    }
}
