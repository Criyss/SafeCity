<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Categorías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3 align-items-center">
                <a href="/mapa"      class="text-white text-decoration-none"><i class="bi bi-map me-1"></i>Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none"><i class="bi bi-bar-chart me-1"></i>Dashboard</a>
                <a href="/usuarios"  class="text-white text-decoration-none"><i class="bi bi-people me-1"></i>Usuarios</a>
                <a href="/categorias" class="text-white text-decoration-none"><i class="bi bi-tags me-1"></i>Categorías</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold" style="color: #1A3A5C;"><i class="bi bi-tags me-2"></i>Gestión de Categorías</h4>
            <a href="/categorias/create" class="btn text-white" style="background-color: #1A3A5C;">
                <i class="bi bi-plus-circle me-1"></i>Nueva Categoría
            </a>
        </div>
        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead style="background-color: #1A3A5C; color: white;">
                        <tr><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                        <tr>
                            <td class="fw-semibold"><i class="bi bi-tag me-1 text-muted"></i>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion }}</td>
                            <td class="d-flex gap-1">
                                <a href="/categorias/{{ $categoria->id }}/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
                                <form action="/categorias/{{ $categoria->id }}/delete" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar categoría?')">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay categorías.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $categorias->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</body>
</html>
