<?php

namespace Database\Seeders;

use App\Models\AreaConocimiento;
use App\Models\Carrera;
use App\Models\Departamento;
use App\Models\Estudiante;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadisticasCargaSeeder extends Seeder
{
    private const STUDENT_COUNT = 10000;

    private const PROJECT_COUNT = 5000;

    /**
     * Generates a repeatable local stress dataset for the UPS statistics screens.
     */
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Los datos de carga solo se insertan en local o testing.');

            return;
        }

        $this->call(EstadisticasVolumenSeeder::class);

        $creador = User::query()->orderBy('id_usuario')->first();
        if ($creador === null) {
            $this->command?->warn('No hay usuarios. Ejecuta primero los seeders de usuarios.');

            return;
        }

        $departamentoNombres = [
            'Facultad de Agronomía (Demo Carga)',
            'Facultad de Ciencias Jurídicas (Demo Carga)',
            'Facultad de Odontología (Demo Carga)',
            'Facultad de Medicina (Demo Carga)',
            'Facultad de Química y Farmacia (Demo Carga)',
            'Facultad de Ciencias y Humanidades (Demo Carga)',
            'Facultad de Ciencias Económicas (Demo Carga)',
            'Facultad de Ingeniería (Demo Carga)',
            'Facultad de Ciencias Naturales (Demo Carga)',
            'Facultad Multidisciplinaria (Demo Carga)',
        ];

        $departamentos = collect($departamentoNombres)
            ->map(fn (string $nombre) => Departamento::firstOrCreate(['nombre' => $nombre]));

        $disciplinas = [
            'Trabajo Social', 'Psicología', 'Periodismo', 'Economía', 'Administración Pública',
            'Contaduría', 'Mercadeo', 'Ingeniería Industrial', 'Ingeniería Civil', 'Arquitectura',
            'Agronomía', 'Veterinaria', 'Biología', 'Química', 'Matemática', 'Informática',
            'Educación', 'Sociología', 'Derecho', 'Nutrición', 'Enfermería', 'Odontología',
            'Medicina', 'Farmacia', 'Historia', 'Letras', 'Física', 'Estadística',
            'Gestión Ambiental', 'Comunicaciones',
        ];
        $niveles = ['Licenciatura en', 'Ingeniería en', 'Técnico en', 'Profesorado en', 'Maestría en'];

        $carrerasCarga = collect(range(1, 50))->map(function (int $index) use ($disciplinas, $niveles, $departamentos) {
            $nombre = sprintf(
                '%s %s %02d (Demo Carga)',
                $niveles[($index - 1) % count($niveles)],
                $disciplinas[($index * 7) % count($disciplinas)],
                $index,
            );

            return Carrera::updateOrCreate(
                ['codigo' => sprintf('DL-%03d', $index)],
                [
                    'nombre' => $nombre,
                    'departamento' => $departamentos->get(intdiv($index - 1, 5))->id_departamento,
                    'horas_servicio_social' => 500,
                ],
            );
        });

        $areasCargaNombres = [
            'Educación inclusiva (Demo Carga)', 'Promoción de la salud (Demo Carga)',
            'Protección ambiental (Demo Carga)', 'Desarrollo económico local (Demo Carga)',
            'Seguridad alimentaria (Demo Carga)', 'Prevención de violencia (Demo Carga)',
            'Arte comunitario (Demo Carga)', 'Patrimonio cultural (Demo Carga)',
            'Alfabetización digital (Demo Carga)', 'Emprendimiento juvenil (Demo Carga)',
            'Agricultura urbana (Demo Carga)', 'Agua y saneamiento (Demo Carga)',
            'Derechos humanos (Demo Carga)', 'Inclusión social (Demo Carga)',
            'Ciencia ciudadana (Demo Carga)', 'Formación laboral (Demo Carga)',
            'Movilidad sostenible (Demo Carga)', 'Bienestar animal (Demo Carga)',
            'Gestión de riesgos (Demo Carga)', 'Participación ciudadana (Demo Carga)',
        ];
        $areasCarga = collect($areasCargaNombres)
            ->map(fn (string $nombre) => AreaConocimiento::firstOrCreate(['nombre' => $nombre]));

        $carreraIds = Carrera::query()
            ->where(fn ($query) => $query->where('codigo', 'like', 'DV-%')->orWhere('codigo', 'like', 'DL-%'))
            ->orderBy('codigo')
            ->pluck('id_carrera')
            ->all();
        $areaIds = AreaConocimiento::query()
            ->whereIn('nombre', array_merge(
                ['Educación y alfabetización (Demo)', 'Salud comunitaria (Demo)', 'Medio ambiente (Demo)', 'Desarrollo comunitario (Demo Volumen)', 'Cultura y patrimonio (Demo Volumen)', 'Agricultura sostenible (Demo Volumen)', 'Tecnología aplicada (Demo Volumen)', 'Participación ciudadana (Demo Volumen)'],
                $areasCargaNombres,
            ))
            ->orderBy('id_area_conocimiento')
            ->pluck('id_area_conocimiento')
            ->all();

        $proyectoCargaBase = Proyecto::withTrashed()
            ->where('titulo', 'like', 'DEMO | Carga UPS %')
            ->min('numero_proyecto');
        $numeroBase = $proyectoCargaBase ?: ((int) Proyecto::withTrashed()->max('numero_proyecto') + 1);

        DB::transaction(function () use ($creador, $carreraIds, $areaIds, $numeroBase): void {
            $now = now();

            for ($start = 1; $start <= self::STUDENT_COUNT; $start += 500) {
                $rows = [];
                for ($index = $start; $index < min($start + 500, self::STUDENT_COUNT + 1); $index++) {
                    $rows[] = [
                        'due' => sprintf('DEMO-CARGA-2026-%05d', $index),
                        'nombre_completo' => sprintf('Estudiante de demostración %05d', $index),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                DB::table('estudiantes')->upsert($rows, ['due'], ['nombre_completo', 'updated_at']);
            }

            $estudiantes = DB::table('estudiantes')
                ->where('due', 'like', 'DEMO-CARGA-2026-%')
                ->orderBy('due')
                ->get(['id_estudiante', 'due']);
            $estudianteIds = $estudiantes->pluck('id_estudiante')->all();

            for ($start = 0; $start < self::STUDENT_COUNT; $start += 500) {
                $careerRows = [];
                for ($index = $start; $index < min($start + 500, self::STUDENT_COUNT); $index++) {
                    $careerRows[] = [
                        'id_estudiante' => $estudianteIds[$index],
                        'id_carrera' => $carreraIds[$index % count($carreraIds)],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                DB::table('carreras_estudiante')->upsert($careerRows, ['id_estudiante', 'id_carrera'], ['updated_at']);
            }

            $fases = ['inscripcion', 'ejecucion', 'memoria', 'finalizado'];
            $titulos = [
                'Jornadas comunitarias de formación', 'Acompañamiento a organizaciones locales',
                'Talleres de prevención y bienestar', 'Recuperación de espacios públicos',
                'Alfabetización y acceso a tecnología', 'Huertos comunitarios sostenibles',
                'Promoción de identidad y cultura', 'Asesoría para iniciativas locales',
                'Capacitación para jóvenes', 'Programa de desarrollo territorial',
                'Campaña de salud preventiva', 'Educación ambiental comunitaria',
            ];
            $tutores = ['Lic. Elena Ramos', 'Ing. Luis Méndez', 'Mtra. Gabriela Flores', 'Dr. Ricardo Peña', 'Lic. Patricia Cruz', 'Ing. Carlos Vásquez'];
            for ($start = 0; $start < self::PROJECT_COUNT; $start += 250) {
                $projectRows = [];
                for ($index = $start; $index < min($start + 250, self::PROJECT_COUNT); $index++) {
                    $numero = $numeroBase + $index;
                    $titulo = sprintf('DEMO | Carga UPS %05d — %s', $index + 1, $titulos[$index % count($titulos)]);
                    $inicio = $now->copy()->subMonths(($index * 7) % 36)->startOfMonth()->addDays($index % 18);
                    $fase = $fases[$index % count($fases)];
                    $participanteIndexes = [
                        $index % self::STUDENT_COUNT,
                        ($index + 1237) % self::STUDENT_COUNT,
                        ($index + 3799) % self::STUDENT_COUNT,
                        ($index + 6113) % self::STUDENT_COUNT,
                    ];
                    $participantesDue = array_map(fn (int $studentIndex) => $estudiantes[$studentIndex]->due, $participanteIndexes);

                    $projectRows[] = [
                        'numero_proyecto' => $numero,
                        'creado_por' => $creador->id_usuario,
                        'fecha_inicio' => $inicio->toDateString(),
                        'fecha_final' => $inicio->copy()->addDays(90 + (($index * 13) % 240))->toDateString(),
                        'titulo' => $titulo,
                        'miembros' => implode(', ', $participantesDue),
                        'tutor' => $tutores[$index % count($tutores)].' (Demo carga)',
                        'subcoordinador' => $tutores[($index + 2) % count($tutores)].' (Demo carga)',
                        'correo_coordinador' => null,
                        'area_de_conocimiento' => $areaIds[$index % count($areaIds)],
                        'documento' => sprintf('DEMO-CARGA-%05d.pdf', $index + 1),
                        'fase_actual' => $fase,
                        'estado_expediente' => $fase === 'finalizado' ? 'finalizado' : ($index % 43 === 0 ? 'cancelado' : 'en_tramite'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                DB::table('proyectos')->upsert(
                    $projectRows,
                    ['numero_proyecto'],
                    ['creado_por', 'fecha_inicio', 'fecha_final', 'titulo', 'miembros', 'tutor', 'subcoordinador', 'correo_coordinador', 'area_de_conocimiento', 'documento', 'fase_actual', 'estado_expediente', 'updated_at'],
                );
            }

            $proyectos = DB::table('proyectos')
                ->where('titulo', 'like', 'DEMO | Carga UPS %')
                ->orderBy('numero_proyecto')
                ->get(['id_proyecto', 'numero_proyecto']);

            for ($start = 0; $start < self::PROJECT_COUNT; $start += 250) {
                $pivotRows = [];
                $certificationRows = [];
                for ($index = $start; $index < min($start + 250, self::PROJECT_COUNT); $index++) {
                    $proyecto = $proyectos[$index];
                    $participanteIndexes = [
                        $index % self::STUDENT_COUNT,
                        ($index + 1237) % self::STUDENT_COUNT,
                        ($index + 3799) % self::STUDENT_COUNT,
                        ($index + 6113) % self::STUDENT_COUNT,
                    ];

                    foreach ($participanteIndexes as $participant => $studentIndex) {
                        $pivotRows[] = [
                            'id_estudiante' => $estudianteIds[$studentIndex],
                            'id_proyecto' => $proyecto->id_proyecto,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];

                        if ($participant >= 2) {
                            continue;
                        }

                        $fechaEmision = $now->copy()->subMonths(($index * 7) % 36)->startOfMonth()->addDays(min($index % 18, 8))->addDays(5 + ($index % 8));
                        $entregada = ($index + $participant) % 4 !== 0;
                        $certificationRows[] = [
                            'proyecto_id' => $proyecto->id_proyecto,
                            'estudiante_id' => $estudianteIds[$studentIndex],
                            'folio' => sprintf('DEMO-CARGA-%08d', ($index * 2) + $participant + 1),
                            'fecha_emision' => $fechaEmision,
                            'emitida_por' => $creador->id_usuario,
                            'fecha_entrega' => $entregada ? $fechaEmision->copy()->addDays(3)->toDateString() : null,
                            'firma_receptor' => $entregada ? 'Recepción de demostración' : null,
                            'entregada_por' => $entregada ? $creador->id_usuario : null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                DB::table('proyectos_estudiantes')->upsert($pivotRows, ['id_estudiante', 'id_proyecto'], ['updated_at']);
                DB::table('certificaciones')->upsert(
                    $certificationRows,
                    ['proyecto_id', 'estudiante_id'],
                    ['folio', 'fecha_emision', 'emitida_por', 'fecha_entrega', 'firma_receptor', 'entregada_por', 'updated_at'],
                );
            }
        });

        $this->command?->info('Carga de demostración lista: 5,000 proyectos, 10,000 estudiantes, 50 carreras, 20 áreas, 20,000 participantes y 10,000 certificaciones.');
    }
}
