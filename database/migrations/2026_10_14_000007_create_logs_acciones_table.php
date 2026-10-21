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
        Schema::create('logs_acciones', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('accion');
            $table->foreignId('usuario')->constrained('usuarios', 'id_usuario')->cascadeOnDelete();
            $table->foreignId('permiso_utilizado')->nullable()->constrained('permissions', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_acciones');
    }
};
