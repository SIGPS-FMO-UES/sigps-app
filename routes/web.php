<?php

use App\Http\Controllers\Admin\AreaConocimientoController;
use App\Http\Controllers\Admin\BitacoraAuditoriaController;
use App\Http\Controllers\Admin\CarreraDepartamentoController;
use App\Http\Controllers\Admin\PlantillaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Publico\CatalogoFormatosController;
use App\Http\Controllers\Ups\BandejaExpedientesController;
use App\Http\Controllers\Ups\ControlPlazosController;
use App\Http\Controllers\Ups\DocumentoProyectoController;
use App\Http\Controllers\Ups\EstadisticasController;
use App\Http\Controllers\Ups\EstudianteController;
use App\Http\Controllers\Ups\GestionProrrogasController;
use App\Http\Controllers\Ups\MemoriaController;
use App\Http\Controllers\Ups\NotificacionController;
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
        Route::post('/notificaciones/{id}/leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leida');
        Route::post('/notificaciones/leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leidas-todas');
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
        Route::get('/estudiantes/buscar', [EstudianteController::class, 'buscar'])->name('estudiantes.buscar');
        Route::post('/estudiantes/rapido', [EstudianteController::class, 'storeRapido'])->name('estudiantes.storeRapido');
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
        Route::get('/memorias', [MemoriaController::class, 'index'])->name('memorias.index');
        Route::post('/proyectos/{id}/memoria/recibir', [MemoriaController::class, 'recibirPorProyecto'])->whereNumber('id')->name('proyectos.memoria.recibir');
        Route::post('/proyectos/{id}/memoria/aprobar', [MemoriaController::class, 'aprobarPorProyecto'])->whereNumber('id')->name('proyectos.memoria.aprobar');
        Route::post('/proyectos/{id}/memoria/pasar-a-firmas', [MemoriaController::class, 'pasarAFirmasPorProyecto'])->whereNumber('id')->name('proyectos.memoria.firmas');
        Route::post('/proyectos/{id}/memoria/marcar-lista', [MemoriaController::class, 'marcarListaPorProyecto'])->whereNumber('id')->name('proyectos.memoria.lista');
        Route::post('/proyectos/{id}/memoria/entregar', [MemoriaController::class, 'entregarPorProyecto'])->whereNumber('id')->name('proyectos.memoria.entregar');
        Route::get('/memorias/{id}', [MemoriaController::class, 'show'])->whereNumber('id')->name('memorias.show');
        Route::get('/memorias/{id}/documento', [MemoriaController::class, 'documento'])->whereNumber('id')->name('memorias.documento');
        Route::get('/documentos/{versionId}/descargar', [MemoriaController::class, 'documento'])->whereNumber('versionId')->name('documentos.descargar');
        Route::post('/memorias/{id}/aprobar', [MemoriaController::class, 'aprobar'])->whereNumber('id')->name('memorias.aprobar');
        Route::post('/memorias/{id}/pasar-a-firmas', [MemoriaController::class, 'pasarAFirmas'])->whereNumber('id')->name('memorias.firmas');
        Route::post('/memorias/{id}/marcar-lista', [MemoriaController::class, 'marcarLista'])->whereNumber('id')->name('memorias.lista');
        Route::post('/memorias/{id}/entregar', [MemoriaController::class, 'entregar'])->whereNumber('id')->name('memorias.entregar');
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
        Route::delete('/areas-conocimiento/{id}', [AreaConocimientoController::class, 'destroy'])->whereNumber('id')->name('areas.destroy');
        Route::get('/plantillas', [PlantillaController::class, 'index'])->name('plantillas.index');
        Route::post('/plantillas', [PlantillaController::class, 'store'])->name('plantillas.store');
        Route::put('/plantillas/{id}', [PlantillaController::class, 'update'])->whereNumber('id')->name('plantillas.update');
        Route::patch('/plantillas/{id}/estado', [PlantillaController::class, 'cambiarEstado'])->whereNumber('id')->name('plantillas.estado');
        Route::get('/carreras-departamentos', [CarreraDepartamentoController::class, 'index'])->name('catalogos.index');
        Route::post('/departamentos', [CarreraDepartamentoController::class, 'storeDepartamento'])->name('departamentos.store');
        Route::put('/departamentos/{id}', [CarreraDepartamentoController::class, 'updateDepartamento'])->whereNumber('id')->name('departamentos.update');
        Route::delete('/departamentos/{id}', [CarreraDepartamentoController::class, 'destroyDepartamento'])->whereNumber('id')->name('departamentos.destroy');
        Route::post('/carreras', [CarreraDepartamentoController::class, 'storeCarrera'])->name('carreras.store');
        Route::put('/carreras/{id}', [CarreraDepartamentoController::class, 'updateCarrera'])->whereNumber('id')->name('carreras.update');
        Route::delete('/carreras/{id}', [CarreraDepartamentoController::class, 'destroyCarrera'])->whereNumber('id')->name('carreras.destroy');
    });
});
