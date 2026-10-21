<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutores', function (Blueprint $table): void {
            $table->id('id_tutor');
            $table->string('nombre')->unique();
            $table->foreignId('area_de_conocimiento')->nullable()
                ->constrained('areas_de_conocimiento', 'id_area_conocimiento')
                ->nullOnDelete();
            $table->date('fecha_revision')->nullable();
            $table->timestamps();
        });

        // Conserva en el nuevo catálogo los nombres que ya estaban guardados en proyectos.
        if (Schema::hasTable('proyectos')) {
            DB::table('proyectos')->whereNotNull('tutor')->where('tutor', '<>', '')
                ->select('tutor', 'area_de_conocimiento')->orderBy('id_proyecto')->get()
                ->each(function (object $proyecto): void {
                    $nombre = trim($proyecto->tutor);
                    if ($nombre === '' || DB::table('tutores')->whereRaw('LOWER(nombre) = ?', [mb_strtolower($nombre)])->exists()) {
                        return;
                    }

                    DB::table('tutores')->insert([
                        'nombre' => $nombre,
                        'area_de_conocimiento' => $proyecto->area_de_conocimiento,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tutores');
    }
};
