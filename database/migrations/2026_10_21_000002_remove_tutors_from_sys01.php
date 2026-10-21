<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tutores');

        if (Schema::hasTable('proyectos') && Schema::hasColumn('proyectos', 'tutor')) {
            Schema::table('proyectos', function (Blueprint $table): void {
                $table->dropColumn('tutor');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('proyectos') && ! Schema::hasColumn('proyectos', 'tutor')) {
            Schema::table('proyectos', function (Blueprint $table): void {
                $table->string('tutor')->nullable();
            });
        }

        if (! Schema::hasTable('tutores')) {
            Schema::create('tutores', function (Blueprint $table): void {
                $table->id('id_tutor');
                $table->string('nombre')->unique();
                $table->foreignId('area_de_conocimiento')->nullable()
                    ->constrained('areas_de_conocimiento', 'id_area_conocimiento')
                    ->nullOnDelete();
                $table->date('fecha_revision')->nullable();
                $table->timestamps();
            });
        }
    }
};
