<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisiones_memorias', function (Blueprint $table): void {
            $table->id('id_revision_memoria');
            $table->foreignId('memoria_id')->constrained('memorias', 'id_memoria')->cascadeOnDelete();
            $table->foreignId('auditor_id')->constrained('usuarios', 'id_usuario')->restrictOnDelete();
            $table->string('resultado', 20);
            $table->boolean('sellos_verificados');
            $table->boolean('tinta_azul_verificada');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['memoria_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisiones_memorias');
    }
};
