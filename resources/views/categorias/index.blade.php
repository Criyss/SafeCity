<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Safe City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Safe City</a>
        <div class="navbar-nav ms-auto d-flex flex-row gap-2 align-items-center">
            <a href="{{ route('usuarios.index') }}" class="nav-link text-white">Usuarios</a>
            <span class="navbar-text text-white">{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gestión de Categorías</h5>
            <a href="{{ route('categorias.create') }}" class="btn btn-light btn-sm">+ Nueva Categoría</a>
        </div>
        <div class="card-body">
            @if($categorias->isEmpty())
                <p class="text-muted text-center py-3">No hay categorías registradas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->id }}</td>
                                <td>{{ $categoria->nombre }}</td>
                                <td class="text-muted">{{ $categoria->descripcion ?? '-' }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
