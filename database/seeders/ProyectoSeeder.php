<?php

namespace Database\Seeders;

use App\Models\AreaConocimiento;
use App\Models\Estudiante;
use App\Models\LogAccion;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyectoSeeder extends Seeder
{
    /**
     * Registra proyectos ficticios y sus participantes para desarrollo local.
     */
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Los proyectos de demostración solo se cargan en local o testing.');

            return;
        }

        $carnetsDemo = [
            'DEMO-2026-0001',
            'DEMO-2026-0002',
            'DEMO-2026-0003',
            'DEMO-2026-0004',
            'DEMO-2026-0005',
            'DEMO-2026-0006',
            'DEMO-2026-0007',
            'DEMO-2026-0008',
            'DEMO-2026-0009',
            'DEMO-2026-0010',
        ];

        if (Estudiante::query()->whereIn('due', $carnetsDemo)->count() < count($carnetsDemo)) {
            $this->call(EstudianteSeeder::class);
        }

        $estudiantes = Estudiante::query()->whereIn('due', $carnetsDemo)->get()->keyBy('due');
        $creador = User::query()->orderBy('id_usuario')->first();

        if ($creador === null || $estudiantes->count() !== count($carnetsDemo)) {
            $this->command?->warn('No se pudieron crear proyectos demo. Ejecuta primero DatabaseSeeder para cargar usuarios y estudiantes.');

            return;
        }

        $areas = collect([
            'Educación y alfabetización (Demo)' => 'Proyectos comunitarios de educación y alfabetización.',
            'Salud comunitaria (Demo)' => 'Jornadas de prevención y educación para la salud.',
            'Medio ambiente (Demo)' => 'Acciones educativas y comunitarias de protección ambiental.',
        ])->mapWithKeys(fn (string $detalles, string $nombre): array => [
            $nombre => AreaConocimiento::firstOrCreate(['nombre' => $nombre], ['detalles' => $detalles]),
        ]);

        $proyectosDemo = [
            [
                'numero_proyecto' => 1001,
                'titulo' => 'DEMO | Alfabetización digital en comunidad rural',
                'meses_inicio' => 5,
                'dias_duracion' => 180,
                'area' => 'Educación y alfabetización (Demo)',
                'tutor' => 'Lic. Carlos Miranda (Demo)',
                'subcoordinador' => 'Ing. Mauricio Peña (Demo)',
                'documento' => 'DEMO-FS-001.pdf',
                'estudiantes' => ['DEMO-2026-0001', 'DEMO-2026-0003', 'DEMO-2026-0005'],
            ],
            [
                'numero_proyecto' => 1002,
                'titulo' => 'DEMO | Jornadas de salud preventiva',
                'meses_inicio' => 3,
                'dias_duracion' => 150,
                'area' => 'Salud comunitaria (Demo)',
                'tutor' => 'Dra. Beatriz Santos (Demo)',
                'subcoordinador' => 'Lic. Ricardo Pineda (Demo)',
                'documento' => 'DEMO-FS-002.pdf',
                'estudiantes' => ['DEMO-2026-0002', 'DEMO-2026-0004', 'DEMO-2026-0006'],
            ],
            [
                'numero_proyecto' => 1003,
                'titulo' => 'DEMO | Reforestación de espacios públicos',
                'meses_inicio' => 7,
                'dias_duracion' => 210,
                'area' => 'Medio ambiente (Demo)',
                'tutor' => 'Ing. Gabriela Ramos (Demo)',
                'subcoordinador' => 'Arq. Elena Flores (Demo)',
                'documento' => 'DEMO-FS-003.pdf',
                'estudiantes' => ['DEMO-2026-0005', 'DEMO-2026-0007', 'DEMO-2026-0008'],
            ],
            [
                'numero_proyecto' => 1004,
                'titulo' => 'DEMO | Taller de alfabetización funcional',
                'meses_inicio' => 2,
                'dias_duracion' => 120,
                'area' => 'Educación y alfabetización (Demo)',
                'tutor' => 'Lic. Manuel Estrada (Demo)',
                'subcoordinador' => 'Mtra. Sofía Hernández (Demo)',
                'documento' => 'DEMO-FS-004.pdf',
                'estudiantes' => ['DEMO-2026-0001', 'DEMO-2026-0009', 'DEMO-2026-0010'],
            ],
            [
                'numero_proyecto' => 1005,
                'titulo' => 'DEMO | Feria comunitaria de hábitos saludables',
                'meses_inicio' => 1,
                'dias_duracion' => 90,
                'area' => 'Salud comunitaria (Demo)',
                'tutor' => 'Dr. Fernando Menjívar (Demo)',
                'subcoordinador' => 'Lic. Andrés Castillo (Demo)',
                'documento' => 'DEMO-FS-005.pdf',
                'estudiantes' => ['DEMO-2026-0002', 'DEMO-2026-0006', 'DEMO-2026-0009'],
            ],
        ];

        DB::transaction(function () use ($proyectosDemo, $areas, $estudiantes, $creador): void {
            foreach ($proyectosDemo as $datos) {
                $participantes = collect($datos['estudiantes'])->map(fn (string $due): Estudiante => $estudiantes->get($due));
                $fechaInicio = now()->subMonths($datos['meses_inicio'])->startOfMonth();

                $proyecto = Proyecto::withTrashed()->firstOrNew(['titulo' => $datos['titulo']]);
                if ($proyecto->trashed()) {
                    $proyecto->restore();
                }

                $proyecto->fill([
                    'numero_proyecto' => $datos['numero_proyecto'],
                    'creado_por' => $creador->id_usuario,
                    'fecha_inicio' => $fechaInicio->toDateString(),
                    'fecha_final' => $fechaInicio->copy()->addDays($datos['dias_duracion'])->toDateString(),
                    'miembros' => $participantes->pluck('due')->join(', '),
                    'tutor' => $datos['tutor'],
                    'subcoordinador' => $datos['subcoordinador'],
                    'area_de_conocimiento' => $areas->get($datos['area'])->id_area_conocimiento,
                    'documento' => $datos['documento'],
                ])->save();

                $proyecto->estudiantes()->sync($participantes->pluck('id_estudiante'));
                LogAccion::firstOrCreate([
                    'accion' => "Registro demo del proyecto #{$proyecto->id_proyecto}: {$proyecto->titulo}",
                    'usuario' => $creador->id_usuario,
                ]);
            }
        });

        $this->command?->info('Se cargaron 5 proyectos demo con estudiantes asignados.');
    }
}
