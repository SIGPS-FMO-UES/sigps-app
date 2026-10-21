<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use App\Models\Certificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EstadisticasController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $this->validar($request);
        $rows = $this->rows($filtros);
        $tipo = $filtros['tipo'] ?? 'mensual';
        $grupos = $rows->groupBy(function (Certificacion $cert) use ($tipo): string {
            return match ($tipo) {
                'anual' => $cert->fecha_entrega->format('Y'),
                'area' => $cert->proyecto?->areaConocimiento?->nombre ?? 'Sin área',
                default => $cert->fecha_entrega->format('Y-m'),
            };
        })->map(fn (Collection $items, string $label) => ['label' => $label, 'entregadas' => $items->count()])->values();

        return view('ups.estadisticas.index', [
            'filtros' => $filtros,
            'grupos' => $grupos,
            'total' => $rows->count(),
            'areas' => AreaConocimiento::query()->orderBy('nombre')->get(),
        ]);
    }

    public function exportar(Request $request): StreamedResponse
    {
        $filtros = $this->validar($request);
        $rows = $this->rows($filtros);

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Folio', 'Fecha de entrega', 'Estudiante', 'Carnet', 'Proyecto', 'Área', 'Firma receptora']);
            foreach ($rows as $cert) {
                fputcsv($out, [$cert->folio, $cert->fecha_entrega?->format('Y-m-d'), $cert->estudiante?->nombre_completo, $cert->estudiante?->due, $cert->proyecto?->titulo, $cert->proyecto?->areaConocimiento?->nombre, $cert->firma_receptor]);
            }
            fclose($out);
        }, 'estadisticas-certificaciones.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'tipo' => ['nullable', 'in:mensual,anual,rango,area'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
            'area_id' => ['nullable', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
        ]);
    }

    private function rows(array $filtros): Collection
    {
        return Certificacion::query()->with(['estudiante', 'proyecto.areaConocimiento'])->whereNotNull('fecha_entrega')
            ->when($filtros['desde'] ?? null, fn ($q, $date) => $q->whereDate('fecha_entrega', '>=', $date))
            ->when($filtros['hasta'] ?? null, fn ($q, $date) => $q->whereDate('fecha_entrega', '<=', $date))
            ->when($filtros['area_id'] ?? null, fn ($q, $id) => $q->whereHas('proyecto', fn ($p) => $p->where('area_de_conocimiento', $id)))
            ->orderBy('fecha_entrega')->get();
    }
}
