<?php

use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\CarreraApiController;
use App\Http\Controllers\Api\V1\DepartamentoApiController;
use App\Http\Controllers\Api\V1\EstadisticaApiController;
use App\Http\Controllers\Api\V1\EstudianteApiController;
use App\Http\Controllers\Api\V1\FormatoApiController;
use App\Http\Controllers\Api\V1\IntegracionApiController;
use App\Http\Controllers\Api\V1\PlazoApiController;
use App\Http\Controllers\Api\V1\ProrrogaApiController;
use App\Http\Controllers\Api\V1\ProyectoApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Backoffice UPS (SYS-01)
|--------------------------------------------------------------------------
|
| Catálogo oficial de endpoints RESTful bajo prefijo /api/v1/.
| Delimitación de arquitectura: Exclusivo Backoffice UPS (ADR-06).
| Referencia canónica: .agents/workflows/endpoints.md (Sección 4).
|
*/

Route::prefix('v1')->group(function () {
    // 0. Ping de Disponibilidad del Servicio
    Route::get('/ping', function () {
        return response()->json([
            'status' => 'ok',
            'sistema' => 'SIGPS Backoffice UPS (SYS-01)',
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    // 1. Catálogo Público de Formatos y Plantillas Oficiales (Épica 9 / HU-43)
    // Permite al Sistema de Coordinadores (SYS-02) y Portal de Estudiantes (SYS-03) consultar y descargar formatos oficiales vigentes (F1 a F8).
    Route::get('/formatos', [FormatoApiController::class, 'index'])->name('api.v1.formatos.index');
    Route::get('/formatos/{id}', [FormatoApiController::class, 'show'])->name('api.v1.formatos.show');
    Route::get('/formatos/{id}/descargar', [FormatoApiController::class, 'descargar'])->name('api.v1.formatos.descargar');

    // 2. Catálogo Público Académico: Carreras y Departamentos (SYS-02 y Portal Estudiantes SYS-03)
    Route::get('/carreras', [CarreraApiController::class, 'index'])->name('api.v1.carreras.index');
    Route::get('/carreras/{id}', [CarreraApiController::class, 'show'])->name('api.v1.carreras.show');
    Route::get('/departamentos', [DepartamentoApiController::class, 'index'])->name('api.v1.departamentos.index');
    Route::get('/departamentos/{id}', [DepartamentoApiController::class, 'show'])->name('api.v1.departamentos.show');
    Route::get('/departamentos/{id}/carreras', [DepartamentoApiController::class, 'carreras'])->name('api.v1.departamentos.carreras');
    Route::get('/catalogo-academico', [CarreraApiController::class, 'catalogo'])->name('api.v1.catalogo.academico');

    // 3. Integración Externa HU-72: Recepción de expedientes avalados desde carreras (SYS-02)
    Route::post('/integracion/expedientes-avalados', [IntegracionApiController::class, 'recibir'])
        ->middleware('carrera.token');

    // 3. Autenticación API con Sanctum
    Route::post('/auth/login', [AuthApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);
        Route::get('/auth/me', [AuthApiController::class, 'me']);

        // 3. Endpoints REST de la Oficina UPS (exclusivo personal UPS)
        Route::middleware(['role:auxiliar_ups|jefe_ups'])->prefix('ups')->group(function () {
            // Estudiantes y Expedientes
            Route::get('/estudiantes', [EstudianteApiController::class, 'index']);
            Route::get('/estudiantes/{identificador}', [EstudianteApiController::class, 'show']);

            // Proyectos
            Route::apiResource('/proyectos', ProyectoApiController::class)->names('api.ups.proyectos');

            // Plazos y Prórrogas
            Route::get('/plazos/alertas', [PlazoApiController::class, 'alertas']);
            Route::post('/prorrogas/{id}/resolver', [ProrrogaApiController::class, 'resolver'])
                ->middleware('permission:prorrogas.resolver');

            // Estadísticas Gerenciales
            Route::get('/estadisticas/resumen', [EstadisticaApiController::class, 'resumen']);
        });
    });
});
