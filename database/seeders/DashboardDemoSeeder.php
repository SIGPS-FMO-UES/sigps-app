<?php

namespace Database\Seeders;

use App\Enums\EstadoMemoria;
use App\Models\Memoria;
use App\Models\Proyecto;
use Illuminate\Database\Seeder;

class DashboardDemoSeeder extends Seeder
{
    /**
     * Completa los proyectos de demostración con memorias en estados operativos distintos.
     */
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Los datos de demostración del dashboard solo se cargan en local o testing.');

            return;
        }

        $this->call(ProyectoSeeder::class);

        $muestras = [
            1001 => [
                'estado' => EstadoMemoria::Observada,
                'dias_desde_recibimiento' => 18,
                'observaciones' => 'DEMO: faltan firmas y sellos de la institución beneficiaria.',
            ],
            1002 => [
                'estado' => EstadoMemoria::EnRevision,
                'dias_desde_recibimiento' => 11,
                'observaciones' => 'DEMO: revisión documental en curso.',
            ],
            1003 => [
                'estado' => EstadoMemoria::Recibido,
                'dias_desde_recibimiento' => 4,
                'observaciones' => 'DEMO: memoria recibida y pendiente de asignación para revisión.',
            ],
            1004 => [
                'estado' => EstadoMemoria::Lista,
                'dias_desde_recibimiento' => 24,
                'observaciones' => 'DEMO: certificación lista para entregar.',
                'fecha_listo_dias' => 3,
            ],
        ];

        $proyectos = Proyecto::query()
            ->whereIn('numero_proyecto', array_keys($muestras))
            ->where('titulo', 'like', 'DEMO |%')
            ->get()
            ->keyBy('numero_proyecto');

        $creadas = 0;

        foreach ($muestras as $numeroProyecto => $muestra) {
            $proyecto = $proyectos->get($numeroProyecto);
            if ($proyecto === null) {
                continue;
            }

            $memoriaExistente = Memoria::withTrashed()
                ->where('numero_proyecto', $numeroProyecto)
                ->exists();

            if ($memoriaExistente) {
                continue;
            }

            Memoria::query()->create([
                'numero_proyecto' => $numeroProyecto,
                'registrada_por' => $proyecto->creado_por,
                'intento' => 1,
                'fecha_recibimiento' => now()->subDays($muestra['dias_desde_recibimiento'])->toDateString(),
                'estado' => $muestra['estado']->value,
                'fecha_listo' => isset($muestra['fecha_listo_dias'])
                    ? now()->subDays($muestra['fecha_listo_dias'])->toDateString()
                    : null,
                'observaciones' => $muestra['observaciones'],
                'documento' => "DEMO-MEMORIA-{$numeroProyecto}.pdf",
            ]);

            $creadas++;
        }

        $this->command?->info("Se agregaron {$creadas} memorias demo sin modificar las existentes.");
    }
}
