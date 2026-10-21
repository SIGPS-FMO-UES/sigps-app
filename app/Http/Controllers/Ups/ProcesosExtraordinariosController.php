<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use App\Models\HistorialFaseProyecto;
use App\Models\LogAccion;
use App\Models\ProcesoExtraordinario;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcesosExtraordinariosController extends Controller
{
    public function retirarEstudiante(Request $request, string $id): RedirectResponse
    {
        $datos = $request->validate([
            'estudiante_id' => ['required', 'integer', 'exists:estudiantes,id_estudiante'],
            'motivo' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        DB::transaction(function () use ($id, $datos): void {
            $proyecto = Proyecto::query()->lockForUpdate()->findOrFail($id);
            abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El proyecto está cerrado.');
            abort_unless($proyecto->estudiantes()->where('estudiantes.id_estudiante', $datos['estudiante_id'])->exists(), 422, 'El estudiante no participa actualmente en este proyecto.');

            ProcesoExtraordinario::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'estudiante_id' => $datos['estudiante_id'],
                'tipo' => 'retiro_estudiante',
                'motivo' => $datos['motivo'],
                'estado' => 'registrado',
                'registrado_por' => auth()->id(),
                'resuelto_por' => auth()->id(),
                'resuelto_at' => now(),
            ]);
            $proyecto->estudiantes()->detach($datos['estudiante_id']);
            HistorialFaseProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => $proyecto->fase_actual,
                'evento' => 'estudiante_retirado',
                'observaciones' => $datos['motivo'],
                'usuario_id' => auth()->id(),
            ]);
            LogAccion::create(['accion' => "Estudiante #{$datos['estudiante_id']} retirado del proyecto #{$proyecto->id_proyecto}", 'usuario' => auth()->id()]);
        });

        return redirect()->route('ups.proyectos.show', $id)->with('success', 'El estudiante fue retirado y el motivo quedó en el historial.');
    }

    public function cancelar(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403);
        $datos = $request->validate(['motivo' => ['required', 'string', 'min:10', 'max:3000']]);

        DB::transaction(function () use ($id, $datos): void {
            $proyecto = Proyecto::query()->lockForUpdate()->findOrFail($id);
            abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El proyecto ya está cerrado.');
            $proyecto->update(['estado_expediente' => 'cancelado', 'fecha_finalizacion' => now()]);
            ProcesoExtraordinario::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'tipo' => 'cancelacion',
                'motivo' => $datos['motivo'],
                'estado' => 'registrado',
                'registrado_por' => auth()->id(),
                'resuelto_por' => auth()->id(),
                'resuelto_at' => now(),
            ]);
            HistorialFaseProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => $proyecto->fase_actual,
                'evento' => 'proyecto_cancelado',
                'observaciones' => $datos['motivo'],
                'usuario_id' => auth()->id(),
            ]);
            LogAccion::create(['accion' => "Proyecto #{$proyecto->id_proyecto} cancelado: {$proyecto->titulo}", 'usuario' => auth()->id()]);
        });

        return redirect()->route('ups.proyectos.show', $id)->with('success', 'El proyecto fue cancelado; el motivo quedó registrado.');
    }

    public function ampliarPlazo(Request $request, string $id): RedirectResponse
    {
        $proyecto = Proyecto::query()->findOrFail($id);
        abort_unless($proyecto->estado_expediente === 'en_tramite', 409);
        $fechaAnterior = $proyecto->fecha_final?->format('Y-m-d') ?? $proyecto->fecha_inicio->format('Y-m-d');
        $datos = $request->validate([
            'fecha_nueva' => ['required', 'date', "after:{$fechaAnterior}"],
            'motivo' => ['required', 'string', 'min:10', 'max:3000'],
            'respaldo' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
        ]);
        $ruta = $request->file('respaldo')?->store("proyectos/{$proyecto->id_proyecto}/respaldos", 'expedientes_ups');

        DB::transaction(function () use ($proyecto, $datos, $ruta, $fechaAnterior): void {
            $proceso = ProcesoExtraordinario::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'tipo' => 'ampliacion_plazo',
                'motivo' => $datos['motivo'],
                'fecha_anterior' => $fechaAnterior,
                'fecha_nueva' => $datos['fecha_nueva'],
                'documento_respaldo' => $ruta,
                'estado' => 'pendiente',
                'registrado_por' => auth()->id(),
            ]);
            LogAccion::create(['accion' => "Solicitud de ampliación de plazo #{$proceso->id_proceso_extraordinario} para el proyecto #{$proyecto->id_proyecto}", 'usuario' => auth()->id()]);
        });

        return redirect()->route('ups.prorrogas.index')->with('success', 'La solicitud de ampliación quedó pendiente de resolución por Jefatura.');
    }

    public function registrarProrroga(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->can('prorrogas.resolver'), 403);
        $proyecto = Proyecto::query()->findOrFail($id);
        abort_unless($proyecto->estado_expediente === 'en_tramite', 409);
        $fechaAnterior = $proyecto->fecha_final?->format('Y-m-d') ?? $proyecto->fecha_inicio->format('Y-m-d');
        $datos = $request->validate([
            'fecha_nueva' => ['required', 'date', "after:{$fechaAnterior}"],
            'autorizacion' => ['required', 'string', 'max:255'],
            'motivo' => ['nullable', 'string', 'max:3000'],
        ]);

        DB::transaction(function () use ($proyecto, $datos, $fechaAnterior): void {
            $proyecto->update(['fecha_final' => $datos['fecha_nueva']]);
            $proceso = ProcesoExtraordinario::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'tipo' => 'prorroga',
                'motivo' => $datos['motivo'] ?? null,
                'fecha_anterior' => $fechaAnterior,
                'fecha_nueva' => $datos['fecha_nueva'],
                'autorizacion' => $datos['autorizacion'],
                'estado' => 'aprobada',
                'resolucion' => $datos['autorizacion'],
                'registrado_por' => auth()->id(),
                'resuelto_por' => auth()->id(),
                'resuelto_at' => now(),
            ]);
            HistorialFaseProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => $proyecto->fase_actual,
                'evento' => 'prorroga_registrada',
                'observaciones' => "Nueva fecha: {$datos['fecha_nueva']}. Autorización: {$datos['autorizacion']}",
                'usuario_id' => auth()->id(),
            ]);
            LogAccion::create(['accion' => "Prórroga #{$proceso->id_proceso_extraordinario} registrada para el proyecto #{$proyecto->id_proyecto}", 'usuario' => auth()->id()]);
        });

        return redirect()->route('ups.proyectos.show', $id)->with('success', 'La prórroga y su autorización quedaron registradas.');
    }
}
