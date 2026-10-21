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
        Schema::table('proyectos', function (Blueprint $table) {
            $table->timestamp('fecha_remision_junta')->nullable();
            $table->foreignId('remitido_junta_por')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('remitido_junta_por');
            $table->dropColumn('fecha_remision_junta');
        });
    }
};
