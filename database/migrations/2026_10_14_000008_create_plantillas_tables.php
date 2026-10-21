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
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id('id_plantilla');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        Schema::create('versiones_plantillas', function (Blueprint $table) {
            $table->id('id_version_plantilla');
            $table->integer('numero_version');
            $table->foreignId('id_plantilla')->constrained('plantillas', 'id_plantilla')->cascadeOnDelete();
            $table->string('tipo')->default('archivo_ejemplo');
            $table->string('nombre_archivo')->nullable();
            $table->string('ruta_archivo')->nullable();
            $table->json('esquema')->nullable();
            $table->boolean('publicada')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versiones_plantillas');
        Schema::dropIfExists('plantillas');
    }
};
