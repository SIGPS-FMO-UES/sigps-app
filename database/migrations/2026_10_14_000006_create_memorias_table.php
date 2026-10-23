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
        Schema::create('memorias', function (Blueprint $table) {
            $table->id('id_memoria');
            $table->foreignId('registrada_por')->constrained('usuarios', 'id_usuario')->restrictOnDelete();
            $table->integer('intento')->default(1);
            $table->date('fecha_recibimiento');
            $table->string('estado')->default('recibido');
            $table->date('fecha_aprovado')->nullable();
            $table->date('fecha_listo')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('numero_proyecto')->unique()->constrained('proyectos', 'numero_proyecto')->cascadeOnDelete();
            $table->string('documento')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorias');
    }
};
