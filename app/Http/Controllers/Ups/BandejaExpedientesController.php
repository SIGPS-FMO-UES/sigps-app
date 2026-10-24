<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\HistorialFaseProyecto;
use App\Models\LogAccion;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BandejaExpedientesController extends Controller
{
    private const FASES = ['inscripcion', 'anteproyecto', 'avance_50', 'memoria_final', 'finalizacion'];

    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'fase' => ['nullable', Rule::in(self::FASES)],
            'estado' => ['nullable', Rule::in(['en_tramite', 'finalizado', 'cancelado'])],
        ]);

        $expedientes = Proyecto::query()->with(['areaConocimiento', 'estudiantes', 'memoria'])
            ->when($filtros['search'] ?? null, fn ($query, $search) => $query->where(fn ($q) => $q
                ->where('titulo', 'ilike', "%{$search}%")
                ->orWhereHas('estudiantes', fn ($estudiantes) => $estudiantes->where('due', 'ilike', "%{$search}%")
                    ->orWhere('nombre_completo', 'ilike', "%{$search}%"))))
            ->when($filtros['fase'] ?? null, fn ($query, $fase) => $query->where('fase_actual', $fase))
            ->when($filtros['estado'] ?? null, fn ($query, $estado) => $query->where('estado_expediente', $estado))
            ->latest('created_at')->paginate(100)->withQueryString();

        return view('ups.bandeja.index', compact('expedientes', 'filtros'));
    }

    public function admitirFase(Request $request, string $id): RedirectResponse
    {
        $datos = $request->validate(['observaciones' => ['nullable', 'string', 'max:2000']]);

        DB::transaction(function () use ($id, $datos): void {
            $proyecto = Proyecto::query()->lockForUpdate()->findOrFail($id);
            abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El expediente ya fue finalizado.');
            $posicion = array_search($proyecto->fase_actual, self::FASES, true);
            abort_if($posicion === false || $proyecto->fase_actual === 'finalizacion', 409, 'No hay una fase pendiente de admisión.');
            $faseAdmitida = $proyecto->fase_actual;

            if ($proyecto->fase_actual === 'memoria_final') {
                $estadoMemoria = $proyecto->memoria?->estado;
                abort_unless($estadoMemoria && in_array($estadoMemoria->value, [
                    EstadoMemoria::Aprobada->value,
                    EstadoMemoria::Aprovada->value,
                    EstadoMemoria::EsperandoFirmas->value,
                    EstadoMemoria::Lista->value,
                    EstadoMemoria::Entregada->value,
                ], true), 422, 'La memoria final debe estar aprobada antes de admitir esta fase.');
            }

            HistorialFaseProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => $faseAdmitida,
                'evento' => 'fase_admitida',
                'observaciones' => $datos['observaciones'] ?? null,
                'usuario_id' => auth()->id(),
            ]);

            $siguienteFase = self::FASES[$posicion + 1];
            $proyecto->update(['fase_actual' => $siguienteFase]);
            LogAccion::create([
                'accion' => "Fase {$faseAdmitida} admitida para el proyecto #{$proyecto->id_proyecto}: {$proyecto->titulo}",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'La fase fue admitida y quedó registrada en el historial del expediente.');
    }

    public function guardarObservaciones(Request $request, string $id, string $fase): RedirectResponse
    {
        abort_unless(in_array($fase, self::FASES, true) && $fase !== 'finalizacion', 404);
        $datos = $request->validate(['observaciones' => ['required', 'string', 'min:3', 'max:3000']]);

        $proyecto = Proyecto::query()->findOrFail($id);
        abort_unless($proyecto->fase_actual === $fase && $proyecto->estado_expediente === 'en_tramite', 409, 'Solo se pueden registrar observaciones para la fase actual abierta.');
        HistorialFaseProyecto::create([
            'proyecto_id' => $proyecto->id_proyecto,
            'fase' => $fase,
            'evento' => 'observaciones_guardadas',
            'observaciones' => $datos['observaciones'],
            'usuario_id' => auth()->id(),
        ]);
        LogAccion::create(['accion' => "Observaciones guardadas en fase {$fase} del proyecto #{$proyecto->id_proyecto}", 'usuario' => auth()->id()]);

        return back()->with('success', 'Las observaciones quedaron registradas en el historial de la fase.');
    }

    public function finalizar(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403);
        $datos = $request->validate(['observaciones' => ['nullable', 'string', 'max:2000']]);

        DB::transaction(function () use ($id, $datos): void {
            $proyecto = Proyecto::query()->lockForUpdate()->findOrFail($id);
            abort_unless($proyecto->estado_expediente === 'en_tramite' && $proyecto->fase_actual === 'finalizacion', 409, 'El proyecto debe completar todas las fases antes de finalizarse.');

            HistorialFaseProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => 'finalizacion',
                'evento' => 'proyecto_finalizado',
                'observaciones' => $datos['observaciones'] ?? null,
                'usuario_id' => auth()->id(),
            ]);

            $proyecto->update([
                'estado_expediente' => 'finalizado',
                'fecha_finalizacion' => now(),
                'fecha_final' => $proyecto->fecha_final ?? now()->toDateString(),
            ]);
            LogAccion::create([
                'accion' => "Proyecto #{$proyecto->id_proyecto} finalizado: {$proyecto->titulo}",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'El proyecto fue finalizado oficialmente.');
    }
}
