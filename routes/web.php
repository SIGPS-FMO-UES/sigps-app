<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
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
    return redirect()->route('login');
})->name('home');

// 2. Autenticación Interna de Personal UPS (Épica 1 / HU-01)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
});
