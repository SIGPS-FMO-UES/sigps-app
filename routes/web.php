<?php

use App\Http\Controllers\Admin\AreaConocimientoController;
use App\Http\Controllers\Admin\BitacoraAuditoriaController;
use App\Http\Controllers\Admin\PlantillaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Publico\CatalogoFormatosController;
use App\Http\Controllers\Ups\BandejaExpedientesController;
use App\Http\Controllers\Ups\CertificacionController;
use App\Http\Controllers\Ups\ControlPlazosController;
use App\Http\Controllers\Ups\DocumentoProyectoController;
use App\Http\Controllers\Ups\EstadisticasController;
use App\Http\Controllers\Ups\EstudianteController;
use App\Http\Controllers\Ups\GestionProrrogasController;
use App\Http\Controllers\Ups\MemoriaController;
use App\Http\Controllers\Ups\ProcesosExtraordinariosController;
use App\Http\Controllers\Ups\ProyectoController;
use App\Http\Controllers\Ups\UpsDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Backoffice UPS (SYS-01)
|--------------------------------------------------------------------------
|
| Especificación técnica canónica: .agents/workflows/endpoints.md
|
*/

// 1. Acceso Público y Redirección
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('ups.dashboard')
        : redirect()->route('login');
})->name('home');
Route::get('/formatos-oficiales', [CatalogoFormatosController::class, 'index'])->name('formatos.index');
Route::get('/formatos-oficiales/{id}/descargar', [CatalogoFormatosController::class, 'descargar'])->whereNumber('id')->name('plantillas.descargar');

// 2. Autenticación Interna de Personal UPS (Épica 1 / HU-01)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');

    Route::middleware('role:auxiliar_ups|jefe_ups')->prefix('ups')->name('ups.')->group(function () {
        Route::get('/dashboard', [UpsDashboardController::class, 'index'])->name('dashboard');
        Route::get('/bandeja', [BandejaExpedientesController::class, 'index'])->name('bandeja.index');
        Route::post('/proyectos/{id}/admitir-fase', [BandejaExpedientesController::class, 'admitirFase'])->whereNumber('id')->name('proyectos.admitirFase');
        Route::post('/proyectos/{id}/finalizar', [BandejaExpedientesController::class, 'finalizar'])->whereNumber('id')->name('proyectos.finalizar');
        Route::post('/proyectos/{id}/fases/{fase}/observaciones', [BandejaExpedientesController::class, 'guardarObservaciones'])->whereNumber('id')->name('proyectos.fase.observaciones');
        Route::get('/proyectos/{id}/expediente', [DocumentoProyectoController::class, 'index'])->whereNumber('id')->name('proyectos.documentos');
        Route::post('/proyectos/{id}/documentos', [DocumentoProyectoController::class, 'store'])->whereNumber('id')->name('proyectos.documentos.store');
        Route::get('/documentos-proyecto/{id}/descargar', [DocumentoProyectoController::class, 'descargar'])->whereNumber('id')->name('documentos-proyecto.descargar');
        Route::post('/proyectos/{id}/retirar-estudiante', [ProcesosExtraordinariosController::class, 'retirarEstudiante'])->whereNumber('id')->name('proyectos.retirar');
        Route::post('/proyectos/{id}/cancelar', [ProcesosExtraordinariosController::class, 'cancelar'])->whereNumber('id')->name('proyectos.cancelar');
        Route::post('/proyectos/{id}/ampliar-plazo', [ProcesosExtraordinariosController::class, 'ampliarPlazo'])->whereNumber('id')->name('proyectos.ampliar');
        Route::post('/proyectos/{id}/registrar-prorroga', [ProcesosExtraordinariosController::class, 'registrarProrroga'])->whereNumber('id')->name('proyectos.prorroga.store');
        Route::get('/prorrogas', [GestionProrrogasController::class, 'index'])->name('prorrogas.index');
        Route::post('/prorrogas/{id}/resolver', [GestionProrrogasController::class, 'resolver'])->whereNumber('id')->middleware('permission:prorrogas.resolver')->name('prorrogas.resolver');
        Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
        Route::get('/estudiantes/crear', [EstudianteController::class, 'create'])->name('estudiantes.create');
        Route::post('/estudiantes', [EstudianteController::class, 'store'])->name('estudiantes.store');
        Route::get('/estudiantes/{id}/editar', [EstudianteController::class, 'edit'])->name('estudiantes.edit');
        Route::put('/estudiantes/{id}', [EstudianteController::class, 'update'])->name('estudiantes.update');
        Route::get('/estudiantes/{id}', [EstudianteController::class, 'show'])->name('estudiantes.show');
        Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
        Route::get('/proyectos/crear', [ProyectoController::class, 'create'])->name('proyectos.create');
        Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
        Route::get('/proyectos/{id}/editar', [ProyectoController::class, 'edit'])->name('proyectos.edit');
        Route::put('/proyectos/{id}', [ProyectoController::class, 'update'])->name('proyectos.update');
        Route::get('/proyectos/{id}/historial', [ProyectoController::class, 'historial'])->whereNumber('id')->name('proyectos.historial');
        Route::get('/proyectos/{id}', [ProyectoController::class, 'show'])->name('proyectos.show');
        // El documento define memorias como parte del ciclo del proyecto, no como CRUD independiente.
        Route::get('/memorias', [MemoriaController::class, 'index'])->name('memorias.index');
        Route::get('/auditoria/{versionId}', [MemoriaController::class, 'auditar'])->whereNumber('versionId')->name('auditoria.show');
        Route::post('/auditoria/{versionId}/dictamen', [MemoriaController::class, 'dictamen'])->whereNumber('versionId')->name('auditoria.store');
        Route::get('/documentos/{versionId}/descargar', [MemoriaController::class, 'documento'])->whereNumber('versionId')->name('documentos.descargar');
        Route::get('/memorias/{id}', [MemoriaController::class, 'show'])->name('memorias.show');
        Route::get('/certificaciones', [CertificacionController::class, 'index'])->name('certificaciones.index');
        Route::post('/certificaciones/{proyectoId}/emitir', [CertificacionController::class, 'emitir'])->whereNumber('proyectoId')->middleware('permission:certificaciones.emitir')->name('certificaciones.emitir');
        Route::post('/certificaciones/{id}/entregar', [CertificacionController::class, 'registrarEntrega'])->whereNumber('id')->name('certificaciones.entregar');
        Route::get('/certificaciones/{id}/imprimir', [CertificacionController::class, 'imprimir'])->whereNumber('id')->name('certificaciones.imprimir');
        Route::get('/plazos', [ControlPlazosController::class, 'index'])->name('plazos.index');
        Route::post('/plazos/{proyectoId}/remitir-junta', [ControlPlazosController::class, 'remitirJunta'])->whereNumber('proyectoId')->middleware('role:jefe_ups')->name('plazos.remitirJunta');
        Route::get('/plazos/{proyectoId}/remision', [ControlPlazosController::class, 'remision'])->whereNumber('proyectoId')->name('plazos.remision');
        Route::get('/estadisticas', [EstadisticasController::class, 'index'])->middleware('permission:estadisticas.consultar|estadisticas.exportar')->name('estadisticas.index');
        Route::get('/estadisticas/exportar', [EstadisticasController::class, 'exportar'])->middleware('permission:estadisticas.exportar')->name('estadisticas.exportar');
    });

    Route::middleware('role:jefe_ups')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/auditoria', [BitacoraAuditoriaController::class, 'index'])->name('auditoria.index');
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::put('/usuarios/{id}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.cambiarEstado');
        Route::get('/areas-conocimiento', [AreaConocimientoController::class, 'index'])->name('areas.index');
        Route::post('/areas-conocimiento', [AreaConocimientoController::class, 'store'])->name('areas.store');
        Route::put('/areas-conocimiento/{id}', [AreaConocimientoController::class, 'update'])->whereNumber('id')->name('areas.update');
        Route::patch('/areas-conocimiento/{id}/estado', [AreaConocimientoController::class, 'cambiarEstado'])->whereNumber('id')->name('areas.estado');
        Route::get('/plantillas', [PlantillaController::class, 'index'])->name('plantillas.index');
        Route::post('/plantillas', [PlantillaController::class, 'store'])->name('plantillas.store');
        Route::put('/plantillas/{id}', [PlantillaController::class, 'update'])->whereNumber('id')->name('plantillas.update');
        Route::patch('/plantillas/{id}/estado', [PlantillaController::class, 'cambiarEstado'])->whereNumber('id')->name('plantillas.estado');
    });
});
