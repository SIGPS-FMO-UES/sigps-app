<?php

namespace Database\Seeders;

use App\Models\Carrera;
use App\Models\Departamento;
use App\Models\Estudiante;
use Illuminate\Database\Seeder;

class EstudianteSeeder extends Seeder
{
    /**
     * Crea datos locales de demostración para probar el directorio de estudiantes.
     */
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Los estudiantes de demostración solo se cargan en local o testing.');

            return;
        }

        $departamento = Departamento::firstOrCreate([
            'nombre' => 'Departamento de Demostración',
        ]);

        $carreras = collect([
            'Ingeniería de Sistemas (Demo)' => ['horas' => 500, 'codigo' => 'I10515'],
            'Administración de Empresas (Demo)' => ['horas' => 500, 'codigo' => 'L10501'],
        ])->mapWithKeys(fn (array $info, string $nombre): array => [
            $nombre => Carrera::firstOrCreate(
                ['nombre' => $nombre],
                ['codigo' => $info['codigo'], 'departamento' => $departamento->id_departamento, 'horas_servicio_social' => $info['horas']]
            ),
        ]);

        $estudiantes = [
            ['due' => 'DEMO-2026-0001', 'nombre_completo' => 'Ana Demo Rivera', 'carrera' => 'Ingeniería de Sistemas (Demo)'],
            ['due' => 'DEMO-2026-0002', 'nombre_completo' => 'Carlos Demo López', 'carrera' => 'Administración de Empresas (Demo)'],
            ['due' => 'DEMO-2026-0003', 'nombre_completo' => 'María Demo García', 'carrera' => 'Ingeniería de Sistemas (Demo)'],
            ['due' => 'DEMO-2026-0004', 'nombre_completo' => 'José Demo Martínez', 'carrera' => 'Administración de Empresas (Demo)'],
            ['due' => 'DEMO-2026-0005', 'nombre_completo' => 'Sofía Demo Hernández', 'carrera' => 'Ingeniería de Sistemas (Demo)'],
            ['due' => 'DEMO-2026-0006', 'nombre_completo' => 'Diego Demo Flores', 'carrera' => 'Administración de Empresas (Demo)'],
            ['due' => 'DEMO-2026-0007', 'nombre_completo' => 'Lucía Demo Cruz', 'carrera' => 'Ingeniería de Sistemas (Demo)'],
            ['due' => 'DEMO-2026-0008', 'nombre_completo' => 'Mateo Demo Reyes', 'carrera' => 'Administración de Empresas (Demo)'],
            ['due' => 'DEMO-2026-0009', 'nombre_completo' => 'Valeria Demo Díaz', 'carrera' => 'Ingeniería de Sistemas (Demo)'],
            ['due' => 'DEMO-2026-0010', 'nombre_completo' => 'Andrés Demo Castillo', 'carrera' => 'Administración de Empresas (Demo)'],
        ];

        foreach ($estudiantes as $datos) {
            $carrera = $carreras->get($datos['carrera']);
            unset($datos['carrera']);

            $estudiante = Estudiante::firstOrCreate(
                ['due' => $datos['due']],
                ['nombre_completo' => $datos['nombre_completo']],
            );

            $estudiante->carreras()->syncWithoutDetaching([$carrera->id_carrera]);
        }

        $this->command?->info('Se cargaron 10 estudiantes y 2 carreras de demostración.');
    }
}
