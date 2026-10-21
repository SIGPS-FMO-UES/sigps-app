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
        Schema::create('certificaciones', function (Blueprint $table) {
            $table->id('id_certificacion');
            $table->foreignId('proyecto_id')->constrained('proyectos', 'id_proyecto')->restrictOnDelete();
            $table->foreignId('estudiante_id')->constrained('estudiantes', 'id_estudiante')->restrictOnDelete();
            $table->string('folio')->nullable()->unique();
            $table->timestamp('fecha_emision');
            $table->foreignId('emitida_por')->constrained('usuarios', 'id_usuario')->restrictOnDelete();
            $table->date('fecha_entrega')->nullable();
            $table->string('firma_receptor')->nullable();
            $table->foreignId('entregada_por')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
            $table->unique(['proyecto_id', 'estudiante_id']);
            $table->index('fecha_entrega');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificaciones');
    }
};
