<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id('id_proyecto');
            $table->unsignedBigInteger('numero_proyecto')->unique();
            $table->foreignId('creado_por')->constrained('usuarios', 'id_usuario')->restrictOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_final')->nullable();
            $table->string('titulo');
            $table->string('miembros')->nullable(); // Campo conservado según DBML; se recomienda usar proyectos_estudiantes
            $table->string('tutor');
            $table->string('subcoordinador');
            $table->string('correo_coordinador')->nullable();
            $table->text('ubicacion');
            $table->foreignId('area_de_conocimiento')->constrained('areas_de_conocimiento', 'id_area_conocimiento')->restrictOnDelete();
            $table->string('documento')->nullable();
            $table->string('fase_actual', 30)->default('inscripcion');
            $table->string('estado_expediente', 30)->default('en_tramite');
            $table->timestamp('fecha_radicado')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->timestamp('fecha_remision_junta')->nullable();
            $table->foreignId('remitido_junta_por')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('proyectos_estudiantes', function (Blueprint $table) {
            $table->id('id_proyecto_estudiante');
            $table->foreignId('id_estudiante')->constrained('estudiantes', 'id_estudiante')->cascadeOnDelete();
            $table->foreignId('id_proyecto')->constrained('proyectos', 'id_proyecto')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['id_estudiante', 'id_proyecto']);
        });

        Schema::create('historial_fases_proyecto', function (Blueprint $table): void {
            $table->id('id_historial_fase');
            $table->foreignId('proyecto_id')->constrained('proyectos', 'id_proyecto')->restrictOnDelete();
            $table->string('fase', 30);
            $table->string('evento', 30);
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
            $table->timestamps();

            $table->index(['proyecto_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_fases_proyecto');
        Schema::dropIfExists('proyectos_estudiantes');
        Schema::dropIfExists('proyectos');
    }
};
