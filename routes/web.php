<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReporteController;

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas públicas para invitados
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Cierre de sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas de administrador
Route::middleware(['auth', 'rol:administrador'])->group(function () {
    Route::get('/usuarios', function () {
        return '<h1>Panel de Administración de Usuarios</h1>';
    });
    Route::get('/categorias', function () {
        return '<h1>CRUD de Categorías</h1>';
    });
});

// Rutas de supervisor y administrador
Route::middleware(['auth', 'rol:administrador,supervisor'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

// Rutas de todos los roles autenticados
Route::middleware(['auth', 'rol:administrador,supervisor,ciudadano'])->group(function () {
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
});

// Rutas de reportes
Route::middleware(['auth'])->group(function () {
    Route::get('/reportes/crear', [ReporteController::class, 'create'])->name('reportes.create');
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
});

// Rutas del mapa
Route::get('/mapa', [App\Http\Controllers\MapaController::class, 'index'])->middleware('auth');
Route::get('/mapa/reportes', [App\Http\Controllers\MapaController::class, 'reportesJson'])->middleware('auth');

// Rutas del mapa público para turistas
Route::get('/mapa-seguridad', [App\Http\Controllers\MapaController::class, 'mapaPublico']);
Route::get('/mapa-seguridad/reportes', [App\Http\Controllers\MapaController::class, 'reportesPublicoJson']);