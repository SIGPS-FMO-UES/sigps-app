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
            $table->foreignId('area_de_conocimiento')->constrained('areas_de_conocimiento', 'id_area_conocimiento')->restrictOnDelete();
            $table->string('documento')->nullable();
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos_estudiantes');
        Schema::dropIfExists('proyectos');
    }
};
