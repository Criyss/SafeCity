<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Safe City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Safe City</a>
        <div class="navbar-nav ms-auto d-flex flex-row gap-2 align-items-center">
            <a href="{{ route('categorias.index') }}" class="nav-link text-white">Categorías</a>
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
            <h5 class="mb-0">Gestión de Usuarios</h5>
            <a href="{{ route('usuarios.create') }}" class="btn btn-light btn-sm">+ Nuevo Usuario</a>
        </div>
        <div class="card-body">
            {{-- Buscador --}}
            <form method="GET" action="{{ route('usuarios.index') }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="buscar" class="form-control form-control-sm"
                           placeholder="Buscar por nombre o correo..."
                           value="{{ request('buscar') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                </div>
            </form>

            @if($usuarios->isEmpty())
                <p class="text-muted text-center py-3">No se encontraron usuarios.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id }}</td>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($usuario->rol) }}</span></td>
                                <td>
                                    @if($usuario->is_active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Bloqueado</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-outline-primary btn-sm">Editar</a>

                                    <form action="{{ route('usuarios.toggle', $usuario->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $usuario->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                onclick="return confirm('¿Confirmar cambio de estado?')">
                                            {{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
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
