<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReporteController;

Route::middleware(['auth'])->group(function () {
    Route::get('/reportes/crear', [ReporteController::class, 'create'])->name('reportes.create');
    Route::post('/reportes', [ReporteController::class, 'store'])->name('reportes.store');
});

// Redirección inicial automática al Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas Públicas para Invitados
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Ruta Protegida de Cierre de Sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas Restringidas por Roles (Integración del Equipo)
Route::middleware(['auth', 'rol:administrador'])->group(function () {
    Route::get('/usuarios', function () {
        return '<h1>Panel de Administración de Usuarios (Estefanía)</h1>';
    });
    Route::get('/categorias', function () {
        return '<h1>CRUD de Categorías (Estefanía)</h1>';
    });
});

Route::middleware(['auth', 'rol:administrador,supervisor'])->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Dashboard Estadístico Nacional (Alaitz)</h1>';
    });
});

Route::middleware(['auth', 'rol:administrador,supervisor,ciudadano'])->group(function () {
    Route::get('/reportes', function () {
        return '<h1>Listado y Gestión de Reportes Urbanos</h1>';
    });
});
