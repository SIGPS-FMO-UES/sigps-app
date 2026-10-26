<?php

namespace App\Services;

use App\Models\Certificacion;
use App\Models\Departamento;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EstadisticasExportService
{
    /**
     * Retorna las plantillas y formatos disponibles para exportación estadística.
     *
     * @return array<string, array{id: string, nombre: string, descripcion: string, formato: string, archivo_plantilla: ?string}>
     */
    public static function plantillasDisponibles(): array
    {
        return [
            'alumnos_departamento' => [
                'id' => 'alumnos_departamento',
                'nombre' => 'Lista de Estudiantes y Proyectos por Departamento',
                'descripcion' => 'Exporta el listado detallado de estudiantes y sus proyectos de servicio social en formato Excel (.xlsx), organizados por departamento sobre la plantilla oficial.',
                'formato' => 'xlsx',
                'archivo_plantilla' => 'plantilla_estudiantes_proyectos.xlsx',
            ],
            'certificaciones_csv' => [
                'id' => 'certificaciones_csv',
                'nombre' => 'Reporte Consolidado de Certificaciones y MINED',
                'descripcion' => 'Exporta las certificaciones emitidas, folios, estados de entrega y firmas en formato CSV compatible con Excel.',
                'formato' => 'csv',
                'archivo_plantilla' => null,
            ],
        ];
    }

    /**
     * Obtiene la ruta física del archivo de plantilla de Excel.
     * Busca primero en storage privado y luego en resources/templates como respaldo institucional.
     */
    public function obtenerRutaPlantilla(string $nombreArchivo = 'plantilla_estudiantes_proyectos.xlsx'): string
    {
        $rutaStorage = storage_path('app/private/plantillas/estadisticas/'.$nombreArchivo);
        if (file_exists($rutaStorage)) {
            return $rutaStorage;
        }

        $rutaResources = resource_path('templates/estadisticas/'.$nombreArchivo);
        if (file_exists($rutaResources)) {
            return $rutaResources;
        }

        throw new RuntimeException("No se encontró la plantilla de exportación [{$nombreArchivo}] en las rutas configuradas.");
    }

    /**
     * Exporta la lista de estudiantes y sus proyectos rellenando la plantilla oficial de Excel.
     * Si no se especifica un departamento, genera una pestaña por cada departamento con estudiantes activos.
     *
     * @param  array<string, mixed>  $filtros
     */
    public function exportarListaAlumnosPorDepartamento(?int $departamentoId = null, array $filtros = []): StreamedResponse
    {
        $rutaPlantilla = $this->obtenerRutaPlantilla('plantilla_estudiantes_proyectos.xlsx');
        $spreadsheet = IOFactory::load($rutaPlantilla);
        $plantillaHoja = $spreadsheet->getSheet(0);

        $query = DB::table('proyectos_estudiantes')
            ->join('proyectos', 'proyectos_estudiantes.id_proyecto', '=', 'proyectos.id_proyecto')
            ->join('estudiantes', 'proyectos_estudiantes.id_estudiante', '=', 'estudiantes.id_estudiante')
            ->leftJoin('carreras_estudiante', 'estudiantes.id_estudiante', '=', 'carreras_estudiante.id_estudiante')
            ->leftJoin('carreras', 'carreras_estudiante.id_carrera', '=', 'carreras.id_carrera')
            ->leftJoin('departamentos', 'carreras.departamento', '=', 'departamentos.id_departamento')
            ->whereNull('proyectos.deleted_at')
            ->when($departamentoId, fn ($q, $id) => $q->where('carreras.departamento', $id))
            ->when($filtros['carrera_id'] ?? null, fn ($q, $id) => $q->where('carreras.id_carrera', $id))
            ->when($filtros['area_id'] ?? null, fn ($q, $id) => $q->where('proyectos.area_de_conocimiento', $id))
            ->when($filtros['desde'] ?? null, fn ($q, $date) => $q->whereDate('proyectos.fecha_inicio', '>=', $date))
            ->when($filtros['hasta'] ?? null, fn ($q, $date) => $q->whereDate('proyectos.fecha_inicio', '<=', $date))
            ->when(($filtros['fase_proyecto'] ?? 'todas') !== 'todas', fn ($q) => $q->where('proyectos.fase_actual', $filtros['fase_proyecto']))
            ->select([
                'departamentos.id_departamento',
                'departamentos.nombre as departamento_nombre',
                'estudiantes.id_estudiante',
                'estudiantes.due',
                'estudiantes.nombre_completo as estudiante_nombre',
                'carreras.nombre as carrera_nombre',
                'proyectos.id_proyecto',
                'proyectos.titulo as proyecto_titulo',
                'proyectos.ubicacion as proyecto_ubicacion',
                'proyectos.tutor as proyecto_tutor',
                'proyectos.subcoordinador as proyecto_subcoordinador',
            ])
            ->distinct()
            ->orderBy('departamentos.nombre')
            ->orderBy('carreras.nombre')
            ->orderBy('estudiantes.nombre_completo');

        $registros = $query->get();

        if ($departamentoId) {
            $departamento = Departamento::find($departamentoId);
            $nombreDepto = $departamento?->nombre ?? 'Departamento';
            $tituloLimpio = $this->sanitizarNombreHoja($nombreDepto, []);
            $plantillaHoja->setTitle($tituloLimpio);
            $this->rellenarHojaEstudiantes($plantillaHoja, $registros);
            $nombreArchivo = 'reporte-alumnos-'.str($nombreDepto)->slug().'-'.now()->format('Ymd_His').'.xlsx';
        } else {
            $agrupados = $registros->groupBy(fn ($item) => $item->departamento_nombre ?: 'Sin Departamento');

            if ($agrupados->isEmpty()) {
                $plantillaHoja->setTitle('Estudiantes');
                $this->rellenarHojaEstudiantes($plantillaHoja, collect());
            } else {
                $hojasCreadas = [];
                foreach ($agrupados as $nombreDepto => $items) {
                    $nuevaHoja = clone $plantillaHoja;
                    $tituloLimpio = $this->sanitizarNombreHoja((string) $nombreDepto, $hojasCreadas);
                    $nuevaHoja->setTitle($tituloLimpio);
                    $hojasCreadas[] = $tituloLimpio;
                    $spreadsheet->addSheet($nuevaHoja);
                    $this->rellenarHojaEstudiantes($nuevaHoja, $items);
                }

                $spreadsheet->removeSheetByIndex(0);
                $spreadsheet->setActiveSheetIndex(0);
            }

            $nombreArchivo = 'reporte-alumnos-por-departamento-'.now()->format('Ymd_His').'.xlsx';
        }

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $nombreArchivo, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Exporta el conjunto de datos de certificaciones en formato CSV compatible con Excel.
     *
     * @param  array<string, mixed>  $filtros
     */
    public function exportarCertificacionesCsv(array $filtros): StreamedResponse
    {
        $query = Certificacion::query()
            ->with(['estudiante.carreras.departamentoRel', 'proyecto.areaConocimiento', 'emisor', 'receptorRegistradoPor'])
            ->whereHas('proyecto')
            ->when($filtros['area_id'] ?? null, fn ($q, $id) => $q->whereHas('proyecto', fn ($p) => $p->where('area_de_conocimiento', $id)))
            ->when($filtros['carrera_id'] ?? null, fn ($q, $id) => $q->whereHas('estudiante.carreras', fn ($c) => $c->where('carreras.id_carrera', $id)))
            ->when($filtros['departamento_id'] ?? null, fn ($q, $id) => $q->whereHas('estudiante.carreras', fn ($c) => $c->where('carreras.departamento', $id)))
            ->when($filtros['desde'] ?? null, fn ($q, $date) => $q->whereDate('fecha_emision', '>=', $date))
            ->when($filtros['hasta'] ?? null, fn ($q, $date) => $q->whereDate('fecha_emision', '<=', $date))
            ->when(($filtros['estado_mined'] ?? null) === 'entregadas', fn ($q) => $q->whereNotNull('fecha_entrega'))
            ->when(($filtros['estado_mined'] ?? null) === 'sin_entregar', fn ($q) => $q->whereNull('fecha_entrega'))
            ->orderBy('fecha_emision');

        $rows = $query->get();
        $nombreArchivo = 'reporte-estadistico-'.(($filtros['tab'] ?? null) === 'mined' ? 'mined' : 'certificaciones').'-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fprintf($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'Folio',
                'Estado Entrega',
                'Fecha Emisión',
                'Fecha Entrega',
                'Carnet / DUE',
                'Estudiante',
                'Carrera',
                'Departamento',
                'N° Proyecto',
                'Proyecto',
                'Área de Conocimiento',
                'Firma / Persona que Recibió',
            ]);

            foreach ($rows as $cert) {
                $carrera = $cert->estudiante?->carreras?->first();
                fputcsv($out, [
                    $cert->folio ?? 'S/F',
                    $cert->fecha_entrega ? 'Entregada' : 'Sin entregar',
                    $cert->fecha_emision?->format('d/m/Y') ?? '—',
                    $cert->fecha_entrega?->format('d/m/Y') ?? 'Pendiente',
                    $cert->estudiante?->due ?? '—',
                    $cert->estudiante?->nombre_completo ?? '—',
                    $carrera?->nombre ?? '—',
                    $carrera?->departamentoRel?->nombre ?? '—',
                    $cert->proyecto?->numero_proyecto ? '#'.$cert->proyecto->numero_proyecto : '—',
                    $cert->proyecto?->titulo ?? '—',
                    $cert->proyecto?->areaConocimiento?->nombre ?? '—',
                    $cert->firma_receptor ?? '—',
                ]);
            }
            fclose($out);
        }, $nombreArchivo, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Rellena una hoja de cálculo con los registros de estudiantes y formatea las filas de acuerdo al estilo de la plantilla.
     *
     * @param  Collection<int, mixed>  $registros
     */
    private function rellenarHojaEstudiantes(Worksheet $sheet, Collection $registros): void
    {
        $filaInicio = 2;
        $filasTotalesPlantilla = 31;
        $totalRegistros = $registros->count();
        $filaActual = $filaInicio;

        foreach ($registros as $index => $item) {
            $correlativo = $index + 1;
            $correo = mb_strtolower((string) $item->due).'@ues.edu.sv';
            $direccionProyecto = $item->proyecto_ubicacion ?: 'No especificada';

            $sheet->setCellValue([1, $filaActual], $correlativo);
            $sheet->setCellValue([2, $filaActual], (string) $item->estudiante_nombre);
            $sheet->setCellValue([3, $filaActual], (string) $item->carrera_nombre);
            $sheet->setCellValue([4, $filaActual], $correo);
            $sheet->setCellValue([5, $filaActual], (string) $item->proyecto_titulo);
            $sheet->setCellValue([6, $filaActual], (string) $direccionProyecto);

            $filaActual++;
        }

        $ultimaFilaConDatos = $filaActual - 1;

        if ($totalRegistros > 0) {
            $rangoDatos = "A{$filaInicio}:F{$ultimaFilaConDatos}";
            $sheet->getStyle($rangoDatos)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFD0D7DE'],
                    ],
                ],
                'font' => [
                    'name' => 'Calibri',
                    'size' => 11,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getStyle("A{$filaInicio}:A{$ultimaFilaConDatos}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("E{$filaInicio}:F{$ultimaFilaConDatos}")
                ->getAlignment()
                ->setWrapText(true);

            if ($ultimaFilaConDatos < $filasTotalesPlantilla) {
                $filasAEliminar = $filasTotalesPlantilla - $ultimaFilaConDatos;
                $sheet->removeRow($ultimaFilaConDatos + 1, $filasAEliminar);
            }
        } else {
            $sheet->setCellValue('A2', '—');
            $sheet->setCellValue('B2', 'No se encontraron estudiantes registrados para los criterios seleccionados.');
            $sheet->getStyle('A2:F2')->applyFromArray([
                'font' => ['italic' => true, 'color' => ['argb' => 'FF64748B']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            if ($filasTotalesPlantilla > 2) {
                $sheet->removeRow(3, $filasTotalesPlantilla - 2);
            }
        }
    }

    /**
     * Sanitiza y trunca el nombre de una pestaña de Excel respetando el límite de 31 caracteres y evitando duplicados.
     *
     * @param  array<int, string>  $hojasExistentes
     */
    private function sanitizarNombreHoja(string $nombre, array $hojasExistentes): string
    {
        $limpio = preg_replace('/[\\\\\\/\\?\\*\\:\\[\\]]/', '-', $nombre) ?? $nombre;
        $limpio = trim(mb_substr($limpio, 0, 28));
        if ($limpio === '') {
            $limpio = 'Departamento';
        }

        $candidato = $limpio;
        $contador = 1;
        while (in_array(mb_strtolower($candidato), array_map('mb_strtolower', $hojasExistentes), true)) {
            $candidato = mb_substr($limpio, 0, 24)." ({$contador})";
            $contador++;
        }

        return $candidato;
    }
}
