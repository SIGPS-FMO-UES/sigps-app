<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_proyecto', function (Blueprint $table): void {
            $table->id('id_documento_proyecto');
            $table->foreignId('proyecto_id')->constrained('proyectos', 'id_proyecto')->restrictOnDelete();
            $table->string('fase', 30)->default('inscripcion');
            $table->string('nombre_original');
            $table->string('ruta');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('tamano_bytes');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
            $table->timestamps();
            $table->index(['proyecto_id', 'fase']);
        });

        Schema::create('procesos_extraordinarios', function (Blueprint $table): void {
            $table->id('id_proceso_extraordinario');
            $table->foreignId('proyecto_id')->constrained('proyectos', 'id_proyecto')->restrictOnDelete();
            $table->foreignId('estudiante_id')->nullable()->constrained('estudiantes', 'id_estudiante')->nullOnDelete();
            $table->string('tipo', 30);
            $table->text('motivo')->nullable();
            $table->date('fecha_anterior')->nullable();
            $table->date('fecha_nueva')->nullable();
            $table->string('autorizacion')->nullable();
            $table->string('documento_respaldo')->nullable();
            $table->string('estado', 20)->default('registrado');
            $table->text('resolucion')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
            $table->foreignId('resuelto_por')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
            $table->timestamp('resuelto_at')->nullable();
            $table->timestamps();
            $table->index(['proyecto_id', 'tipo', 'created_at']);
            $table->index(['estado', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procesos_extraordinarios');
        Schema::dropIfExists('documentos_proyecto');
    }
};
