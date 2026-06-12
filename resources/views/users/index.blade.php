<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/mapa" class="text-white text-decoration-none">Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none">Dashboard</a>
                <a href="/usuarios" class="text-white text-decoration-none">Usuarios</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold" style="color: #1A3A5C;">Gestión de Usuarios</h4>
            <a href="/usuarios/create" class="btn text-white" style="background-color: #1A3A5C;">+ Nuevo Usuario</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="/usuarios" class="d-flex gap-2">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o correo..." value="{{ $buscar ?? '' }}">
                    <button type="submit" class="btn text-white" style="background-color: #1A3A5C;">Buscar</button>
                    <a href="/usuarios" class="btn btn-outline-secondary">Limpiar</a>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead style="background-color: #1A3A5C; color: white;">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="badge" style="background-color: #1A3A5C;">{{ $usuario->rol }}</span>
                            </td>
                            <td>
                                @if($usuario->is_active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Bloqueado</span>
                                @endif
                            </td>
                            <td>
                                <a href="/usuarios/{{ $usuario->id }}/edit" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="/usuarios/{{ $usuario->id }}/toggle" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        {{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                                    </button>
                                </form>
                                <form action="/usuarios/{{ $usuario->id }}/delete" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay usuarios</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
</body>
</html>