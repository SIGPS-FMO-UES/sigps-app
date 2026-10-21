<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table): void {
            $table->string('fase_actual', 30)->default('inscripcion');
            $table->string('estado_expediente', 30)->default('en_tramite');
            $table->timestamp('fecha_radicado')->nullable();
            $table->timestamp('fecha_finalizacion')->nullable();
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

    public function down(): void
    {
        Schema::dropIfExists('historial_fases_proyecto');
        Schema::table('proyectos', function (Blueprint $table): void {
            $table->dropColumn(['fase_actual', 'estado_expediente', 'fecha_radicado', 'fecha_finalizacion']);
        });
    }
};
