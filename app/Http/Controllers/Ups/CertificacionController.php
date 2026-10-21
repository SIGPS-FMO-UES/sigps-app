<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use App\Models\Certificacion;
use App\Models\Memoria;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CertificacionController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'area_id' => ['nullable', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
            'fecha' => ['nullable', 'date'],
        ]);
        $memorias = Memoria::query()->with(['proyecto.estudiantes', 'proyecto.areaConocimiento', 'proyecto.certificaciones'])
            ->whereIn('estado', [EstadoMemoria::Aprobada->value, EstadoMemoria::Aprovada->value, EstadoMemoria::EsperandoFirmas->value, EstadoMemoria::Lista->value])
            ->when($filtros['area_id'] ?? null, fn ($q, $id) => $q->whereHas('proyecto', fn ($p) => $p->where('area_de_conocimiento', $id)))
            ->when($filtros['fecha'] ?? null, fn ($q, $date) => $q->where(fn ($dates) => $dates->whereDate('fecha_listo', $date)->orWhere(fn ($x) => $x->whereNull('fecha_listo')->whereDate('fecha_aprovado', $date))))
            ->when($filtros['search'] ?? null, fn ($q, $term) => $q->whereHas('proyecto', fn ($p) => $p->where('titulo', 'ilike', "%{$term}%")
                ->orWhereHas('estudiantes', fn ($s) => $s->where('due', 'ilike', "%{$term}%")->orWhere('nombre_completo', 'ilike', "%{$term}%"))))
            ->latest('fecha_listo')->paginate(12)->withQueryString();
        $certificaciones = Certificacion::query()->with(['estudiante', 'proyecto.areaConocimiento'])->latest('fecha_emision')->paginate(12, ['*'], 'certificaciones_page')->withQueryString();

        return view('ups.certificaciones.index', [
            'memorias' => $memorias,
            'certificaciones' => $certificaciones,
            'filtros' => $filtros,
            'areas' => AreaConocimiento::query()->where('activa', true)->orderBy('nombre')->get(),
        ]);
    }

    public function emitir(Request $request, string $proyectoId): RedirectResponse
    {
        $data = $request->validate(['estudiante_id' => ['required', 'integer', 'exists:estudiantes,id_estudiante']]);
        $certificacion = DB::transaction(function () use ($proyectoId, $data): Certificacion {
            $proyecto = Proyecto::query()->with('memoria')->lockForUpdate()->findOrFail($proyectoId);
            abort_unless($proyecto->estudiantes()->where('estudiantes.id_estudiante', $data['estudiante_id'])->exists(), 422, 'El estudiante no pertenece al proyecto.');
            abort_unless($proyecto->memoria && in_array($proyecto->memoria->estado->value, [EstadoMemoria::Aprobada->value, EstadoMemoria::Aprovada->value, EstadoMemoria::EsperandoFirmas->value, EstadoMemoria::Lista->value], true), 422, 'La memoria todavía no está aprobada para certificación.');
            abort_if(Certificacion::query()->where('proyecto_id', $proyectoId)->where('estudiante_id', $data['estudiante_id'])->exists(), 409, 'Ya existe una certificación para este estudiante y proyecto.');
            $cert = Certificacion::create(['proyecto_id' => $proyectoId, 'estudiante_id' => $data['estudiante_id'], 'fecha_emision' => now(), 'emitida_por' => auth()->id()]);
            $cert->update(['folio' => sprintf('UPS-%s-%06d', now()->format('Y'), $cert->id_certificacion)]);

            return $cert;
        });

        return back()->with('success', "Certificación {$certificacion->folio} emitida.");
    }

    public function registrarEntrega(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate(['fecha_entrega' => ['required', 'date', 'before_or_equal:today'], 'firma_receptor' => ['required', 'string', 'max:150']]);
        $cert = Certificacion::query()->findOrFail($id);
        abort_if($cert->fecha_entrega, 409, 'Esta certificación ya fue entregada.');
        $cert->update($data + ['entregada_por' => auth()->id()]);

        return back()->with('success', "Entrega de {$cert->folio} registrada.");
    }

    public function imprimir(string $id): View
    {
        return view('ups.certificaciones.imprimir', ['certificacion' => Certificacion::query()->with(['proyecto.areaConocimiento', 'estudiante'])->findOrFail($id)]);
    }
}
