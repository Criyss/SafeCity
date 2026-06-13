<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Categorías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .pagination { font-size: 1rem !important; }
        .pagination svg { width: 16px !important; height: 16px !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/mapa" class="text-white text-decoration-none">Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none">Dashboard</a>
                <a href="/usuarios" class="text-white text-decoration-none">Usuarios</a>
                <a href="/categorias" class="text-white text-decoration-none">Categorías</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold" style="color: #1A3A5C;">Gestión de Categorías</h4>
            <a href="/categorias/create" class="btn text-white" style="background-color: #1A3A5C;">+ Nueva Categoría</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead style="background-color: #1A3A5C; color: white;">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion }}</td>
                            <td>
                                <a href="/categorias/{{ $categoria->id }}/edit" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="/categorias/{{ $categoria->id }}/delete" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar categoría?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay categorías</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $categorias->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</body>
</html>