<?php

namespace Database\Seeders;

use App\Models\AreaConocimiento;
use App\Models\Carrera;
use App\Models\Certificacion;
use App\Models\Departamento;
use App\Models\Estudiante;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadisticasVolumenSeeder extends Seeder
{
    /**
     * Adds a repeatable local dataset to exercise statistics, search and pagination.
     */
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Los datos de volumen solo se cargan en local o testing.');

            return;
        }

        $creador = User::query()->orderBy('id_usuario')->first();
        if ($creador === null) {
            $this->command?->warn('No hay usuarios. Ejecuta primero los seeders de usuarios.');

            return;
        }

        $departamentosNombres = [
            'Ciencias y Humanidades (Demo Volumen)',
            'Ciencias Económicas (Demo Volumen)',
            'Ingeniería y Arquitectura (Demo Volumen)',
            'Ciencias Naturales y Matemática (Demo Volumen)',
        ];

        $departamentos = collect($departamentosNombres)
            ->map(fn (string $nombre) => Departamento::firstOrCreate(['nombre' => $nombre]));

        $carrerasNombres = [
            'Trabajo Social', 'Periodismo', 'Psicología',
            'Administración de Empresas', 'Contaduría Pública', 'Mercadeo',
            'Ingeniería Industrial', 'Arquitectura', 'Ingeniería Civil',
            'Biología', 'Química', 'Matemática',
        ];

        $carreras = collect($carrerasNombres)->map(function (string $nombre, int $index) use ($departamentos) {
            $departamento = $departamentos->get(intdiv($index, 3));

            return Carrera::updateOrCreate(
                ['codigo' => sprintf('DV-%02d', $index + 1)],
                [
                    'nombre' => "{$nombre} (Demo Volumen)",
                    'departamento' => $departamento->id_departamento,
                    'horas_servicio_social' => 500,
                ],
            );
        });

        $areasNombres = [
            'Educación y alfabetización (Demo)',
            'Salud comunitaria (Demo)',
            'Medio ambiente (Demo)',
            'Desarrollo comunitario (Demo Volumen)',
            'Cultura y patrimonio (Demo Volumen)',
            'Agricultura sostenible (Demo Volumen)',
            'Tecnología aplicada (Demo Volumen)',
            'Participación ciudadana (Demo Volumen)',
        ];
        $areas = collect($areasNombres)->map(fn (string $nombre) => AreaConocimiento::firstOrCreate(['nombre' => $nombre]));

        $nombres = ['Ana', 'Carlos', 'María', 'José', 'Sofía', 'Diego', 'Lucía', 'Mateo', 'Valeria', 'Andrés', 'Camila', 'Daniel'];
        $apellidos = ['Rivera', 'López', 'García', 'Martínez', 'Hernández', 'Flores', 'Cruz', 'Reyes', 'Díaz', 'Castillo', 'Mendoza', 'Pérez', 'Ramos', 'Vásquez', 'Morales', 'Aguilar', 'Mejía', 'Alvarado', 'Romero', 'Fuentes'];

        $estudiantes = collect();
        for ($index = 1; $index <= 240; $index++) {
            $due = sprintf('DEMO-VOL-2026-%04d', $index);
            $nombre = sprintf(
                '%s %s %s',
                $nombres[($index - 1) % count($nombres)],
                $apellidos[intdiv($index - 1, count($nombres)) % count($apellidos)],
                $apellidos[($index * 7) % count($apellidos)],
            );

            $estudiante = Estudiante::updateOrCreate(['due' => $due], ['nombre_completo' => $nombre]);
            $estudiante->carreras()->syncWithoutDetaching([$carreras->get(($index - 1) % $carreras->count())->id_carrera]);
            $estudiantes->put($index - 1, $estudiante);
        }

        $fases = ['inscripcion', 'ejecucion', 'memoria', 'finalizado'];
        $titulos = [
            'Jornadas de acompañamiento comunitario',
            'Talleres de formación para jóvenes',
            'Programa de salud preventiva',
            'Recuperación de espacios públicos',
            'Alfabetización y acceso digital',
            'Huertos escolares y sostenibilidad',
            'Promoción de cultura local',
            'Asesoría para organizaciones comunitarias',
            'Campaña de participación ciudadana',
            'Capacitación para emprendimientos locales',
        ];
        $tutores = ['Lic. Elena Ramos', 'Ing. Luis Méndez', 'Mtra. Gabriela Flores', 'Dr. Ricardo Peña', 'Lic. Patricia Cruz', 'Ing. Carlos Vásquez'];

        $proyectoVolumenBase = Proyecto::withTrashed()
            ->where('titulo', 'like', 'DEMO | Volumen UPS %')
            ->min('numero_proyecto');
        $numeroBase = $proyectoVolumenBase ?: ((int) Proyecto::withTrashed()->max('numero_proyecto') + 1);

        DB::transaction(function () use ($creador, $carreras, $areas, $estudiantes, $fases, $titulos, $tutores, $numeroBase): void {
            for ($index = 0; $index < 120; $index++) {
                $numero = $numeroBase + $index;
                $titulo = sprintf('DEMO | Volumen UPS %03d — %s', $index + 1, $titulos[$index % count($titulos)]);
                $participantes = collect([
                    $index % 240,
                    ($index + 59) % 240,
                    ($index + 127) % 240,
                    ($index + 199) % 240,
                ])->map(fn (int $estudianteIndex) => $estudiantes->get($estudianteIndex));

                $inicio = now()->copy()->subMonths(($index * 5) % 34)->startOfMonth()->addDays($index % 18);
                $fase = $fases[$index % count($fases)];
                $estadoExpediente = $fase === 'finalizado'
                    ? 'finalizado'
                    : ($index % 29 === 0 ? 'cancelado' : 'en_tramite');

                $proyecto = Proyecto::withTrashed()->firstOrNew(['titulo' => $titulo]);
                if ($proyecto->trashed()) {
                    $proyecto->restore();
                }

                $proyecto->fill([
                    'numero_proyecto' => $numero,
                    'creado_por' => $creador->id_usuario,
                    'fecha_inicio' => $inicio->toDateString(),
                    'fecha_final' => $inicio->copy()->addDays(90 + (($index * 13) % 240))->toDateString(),
                    'titulo' => $titulo,
                    'miembros' => $participantes->pluck('due')->join(', '),
                    'tutor' => $tutores[$index % count($tutores)].' (Demo)',
                    'subcoordinador' => $tutores[($index + 2) % count($tutores)].' (Demo)',
                    'area_de_conocimiento' => $areas->get($index % $areas->count())->id_area_conocimiento,
                    'documento' => sprintf('DEMO-VOLUMEN-%03d.pdf', $index + 1),
                    'fase_actual' => $fase,
                    'estado_expediente' => $estadoExpediente,
                ])->save();

                $proyecto->estudiantes()->sync($participantes->pluck('id_estudiante'));

                $certificadosPorProyecto = $index % 3 === 0 ? 3 : 2;
                for ($participante = 0; $participante < $certificadosPorProyecto; $participante++) {
                    $estudiante = $participantes->get($participante);
                    $fechaEmision = $inicio->copy()->addDays(10 + ($index % 10));
                    if ($fechaEmision->isFuture()) {
                        $fechaEmision = now()->copy()->subDay();
                    }
                    $entregada = ($index + $participante) % 5 !== 0;
                    $fechaEntrega = $entregada ? $fechaEmision->copy()->addDays(3) : null;
                    if ($fechaEntrega?->isFuture()) {
                        $fechaEntrega = now();
                    }

                    Certificacion::updateOrCreate(
                        [
                            'proyecto_id' => $proyecto->id_proyecto,
                            'estudiante_id' => $estudiante->id_estudiante,
                        ],
                        [
                            'folio' => sprintf('DEMO-VOL-%06d', ($index * 3) + $participante + 1),
                            'fecha_emision' => $fechaEmision,
                            'emitida_por' => $creador->id_usuario,
                            'fecha_entrega' => $fechaEntrega?->toDateString(),
                            'firma_receptor' => $entregada ? 'Recepción de demostración' : null,
                            'entregada_por' => $entregada ? $creador->id_usuario : null,
                        ],
                    );
                }
            }
        });

        $this->command?->info('Datos de volumen listos: 120 proyectos, 240 estudiantes, 12 carreras, 8 áreas y 280 certificaciones de demostración.');
    }
}
