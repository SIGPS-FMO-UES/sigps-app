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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id('id_estudiante');
            $table->string('due')->unique();
            $table->string('nombre_completo');
            $table->timestamps();
        });

        Schema::create('carreras_estudiante', function (Blueprint $table) {
            $table->id('id_carrera_estudiante');
            $table->foreignId('id_estudiante')->constrained('estudiantes', 'id_estudiante')->cascadeOnDelete();
            $table->foreignId('id_carrera')->constrained('carreras', 'id_carrera')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['id_estudiante', 'id_carrera']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carreras_estudiante');
        Schema::dropIfExists('estudiantes');
    }
};
