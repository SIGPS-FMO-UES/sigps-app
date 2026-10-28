<?php

namespace Tests\Feature;

use App\Enums\EstadoMemoria;
use App\Models\AreaConocimiento;
use App\Models\Memoria;
use App\Models\Proyecto;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemoriaYFinalizacionProyectoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_auxiliar_puede_marcar_memoria_como_entregada_desde_proyecto(): void
    {
        $usuario = User::factory()->create();
        $usuario->assignRole('auxiliar_ups');

        $area = AreaConocimiento::create(['nombre' => 'Ingeniería', 'activa' => true]);

        $proyecto = Proyecto::create([
            'numero_proyecto' => 101,
            'titulo' => 'Proyecto de Prueba',
            'creado_por' => $usuario->id_usuario,
            'fecha_inicio' => now()->subMonths(2)->toDateString(),
            'tutor' => 'Docente Tutor',
            'subcoordinador' => 'Subcoordinador',
            'ubicacion' => 'San Salvador',
            'area_de_conocimiento' => $area->id_area_conocimiento,
            'fase_actual' => 'memoria_final',
            'estado_expediente' => 'en_tramite',
        ]);

        $memoria = Memoria::create([
            'numero_proyecto' => $proyecto->numero_proyecto,
            'registrada_por' => $usuario->id_usuario,
            'fecha_recibimiento' => now()->subDays(5)->toDateString(),
            'estado' => EstadoMemoria::Aprobada,
        ]);

        $fechaEntrega = now()->toDateString();

        $response = $this->actingAs($usuario)->post(route('ups.proyectos.memoria.entregar', $proyecto->id_proyecto), [
            'fecha_entrega' => $fechaEntrega,
            'observaciones' => 'Entrega presencial con sellos.',
        ]);

        $response->assertRedirect();
        $memoria->refresh();

        $this->assertEquals(EstadoMemoria::Entregada, $memoria->estado);
        $this->assertEquals($fechaEntrega, $memoria->fecha_entrega->toDateString());
        $this->assertStringContainsString('Entrega presencial', $memoria->observaciones);
    }

    public function test_jefe_ups_puede_finalizar_proyecto_en_finalizacion(): void
    {
        $jefe = User::factory()->create();
        $jefe->assignRole('jefe_ups');

        $area = AreaConocimiento::create(['nombre' => 'Medicina', 'activa' => true]);

        $proyecto = Proyecto::create([
            'numero_proyecto' => 102,
            'titulo' => 'Proyecto Salud Comunitaria',
            'creado_por' => $jefe->id_usuario,
            'fecha_inicio' => now()->subMonths(6)->toDateString(),
            'tutor' => 'Dra. Tutora',
            'subcoordinador' => 'Subcoordinador',
            'ubicacion' => 'Santa Ana',
            'area_de_conocimiento' => $area->id_area_conocimiento,
            'fase_actual' => 'finalizacion',
            'estado_expediente' => 'en_tramite',
        ]);

        $response = $this->actingAs($jefe)->post(route('ups.proyectos.finalizar', $proyecto->id_proyecto), [
            'observaciones' => 'Expediente concluido satisfactoriamente.',
        ]);

        $response->assertRedirect();
        $proyecto->refresh();

        $this->assertEquals('finalizado', $proyecto->estado_expediente);
        $this->assertNotNull($proyecto->fecha_finalizacion);
    }

    public function test_jefe_ups_puede_finalizar_proyecto_directamente_con_memoria_aprobada(): void
    {
        $jefe = User::factory()->create();
        $jefe->assignRole('jefe_ups');

        $area = AreaConocimiento::create(['nombre' => 'Ciencias', 'activa' => true]);

        $proyecto = Proyecto::create([
            'numero_proyecto' => 103,
            'titulo' => 'Proyecto Ciencias',
            'creado_por' => $jefe->id_usuario,
            'fecha_inicio' => now()->subMonths(4)->toDateString(),
            'tutor' => 'Tutor Ciencias',
            'subcoordinador' => 'Subcoordinador',
            'ubicacion' => 'San Miguel',
            'area_de_conocimiento' => $area->id_area_conocimiento,
            'fase_actual' => 'memoria_final',
            'estado_expediente' => 'en_tramite',
        ]);

        $memoria = Memoria::create([
            'numero_proyecto' => $proyecto->numero_proyecto,
            'registrada_por' => $jefe->id_usuario,
            'fecha_recibimiento' => now()->subDays(10)->toDateString(),
            'estado' => EstadoMemoria::Aprobada,
        ]);

        $response = $this->actingAs($jefe)->post(route('ups.proyectos.finalizar', $proyecto->id_proyecto), [
            'observaciones' => 'Finalización directa desde memoria final.',
        ]);

        $response->assertRedirect();
        $proyecto->refresh();
        $memoria->refresh();

        $this->assertEquals('finalizado', $proyecto->estado_expediente);
        $this->assertEquals('finalizacion', $proyecto->fase_actual);
        $this->assertEquals(EstadoMemoria::Entregada, $memoria->estado);
        $this->assertNotNull($memoria->fecha_entrega);
    }

    public function test_auxiliar_ups_no_puede_finalizar_proyecto(): void
    {
        $auxiliar = User::factory()->create();
        $auxiliar->assignRole('auxiliar_ups');

        $area = AreaConocimiento::create(['nombre' => 'Humanidades', 'activa' => true]);

        $proyecto = Proyecto::create([
            'numero_proyecto' => 104,
            'titulo' => 'Proyecto Humanidades',
            'creado_por' => $auxiliar->id_usuario,
            'fecha_inicio' => now()->subMonths(3)->toDateString(),
            'tutor' => 'Tutor Humanidades',
            'subcoordinador' => 'Subcoordinador',
            'ubicacion' => 'San Salvador',
            'area_de_conocimiento' => $area->id_area_conocimiento,
            'fase_actual' => 'finalizacion',
            'estado_expediente' => 'en_tramite',
        ]);

        $response = $this->actingAs($auxiliar)->post(route('ups.proyectos.finalizar', $proyecto->id_proyecto));

        $response->assertForbidden();
        $proyecto->refresh();
        $this->assertEquals('en_tramite', $proyecto->estado_expediente);
    }
}
