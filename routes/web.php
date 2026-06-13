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
    // Usuarios
    Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [App\Http\Controllers\UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [App\Http\Controllers\UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('usuarios.edit');
    Route::post('/usuarios/{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('usuarios.update');
    Route::post('/usuarios/{user}/delete', [App\Http\Controllers\UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::post('/usuarios/{user}/toggle', [App\Http\Controllers\UserController::class, 'toggleActivo'])->name('usuarios.toggle');

    // Categorías
    Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [App\Http\Controllers\CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [App\Http\Controllers\CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/edit', [App\Http\Controllers\CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::post('/categorias/{categoria}/update', [App\Http\Controllers\CategoriaController::class, 'update'])->name('categorias.update');
    Route::post('/categorias/{categoria}/delete', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('categorias.destroy');
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