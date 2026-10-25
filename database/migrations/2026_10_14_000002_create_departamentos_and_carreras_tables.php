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
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id('id_departamento');
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('carreras', function (Blueprint $table) {
            $table->id('id_carrera');
            $table->string('codigo', 50)->nullable()->unique();
            $table->string('nombre');
            $table->foreignId('departamento')->constrained('departamentos', 'id_departamento')->cascadeOnDelete();
            $table->unsignedInteger('horas_servicio_social')->default(500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carreras');
        Schema::dropIfExists('departamentos');
    }
};
