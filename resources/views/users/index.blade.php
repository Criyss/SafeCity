<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Usuarios</title>
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
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold" style="color: #1A3A5C;"><i class="bi bi-people me-2"></i>Gestión de Usuarios</h4>
            <a href="/usuarios/create" class="btn text-white" style="background-color: #1A3A5C;">
                <i class="bi bi-person-plus me-1"></i>Nuevo Usuario
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="/usuarios" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small mb-1"><i class="bi bi-search me-1"></i>Buscar</label>
                        <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Nombre o correo..." value="{{ $buscar ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1"><i class="bi bi-person-badge me-1"></i>Rol</label>
                        <select name="rol" class="form-select form-select-sm">
                            <option value="">Todos los roles</option>
                            <option value="administrador" {{ ($filtroRol ?? '') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="supervisor"    {{ ($filtroRol ?? '') === 'supervisor'    ? 'selected' : '' }}>Supervisor</option>
                            <option value="ciudadano"     {{ ($filtroRol ?? '') === 'ciudadano'     ? 'selected' : '' }}>Ciudadano</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1"><i class="bi bi-toggle-on me-1"></i>Estado</label>
                        <select name="estado" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <option value="1" {{ ($filtroEst ?? '') === '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ ($filtroEst ?? '') === '0' ? 'selected' : '' }}>Bloqueado</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm text-white" style="background-color: #1A3A5C;"><i class="bi bi-search me-1"></i>Buscar</button>
                        <a href="/usuarios" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Limpiar</a>
                    </div>
                </form>
            </div>
        </div>

        @php
            $personal    = $usuarios->filter(fn($u) => in_array($u->rol, ['administrador','supervisor']));
            $ciudadanos  = $usuarios->filter(fn($u) => $u->rol === 'ciudadano');
        @endphp

        {{-- Personal del sistema --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold" style="background-color: #1A3A5C; color: white;">
                <i class="bi bi-shield-lock me-2"></i>Personal del Sistema
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        @forelse($personal as $usuario)
                        <tr>
                            <td class="fw-semibold"><i class="bi bi-person-badge me-1 text-muted"></i>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @if($usuario->rol === 'administrador')
                                    <span class="badge" style="background-color: #1A3A5C;">administrador</span>
                                @else
                                    <span class="badge bg-secondary">supervisor</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->is_active)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Bloqueado</span>
                                @endif
                            </td>
                            <td class="d-flex gap-1 flex-wrap">
                                <a href="/usuarios/{{ $usuario->id }}/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
                                <form action="/usuarios/{{ $usuario->id }}/toggle" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-toggle-{{ $usuario->is_active ? 'on' : 'off' }} me-1"></i>{{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                                    </button>
                                </form>
                                <form action="/usuarios/{{ $usuario->id }}/delete" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No hay administradores ni supervisores.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Ciudadanos registrados --}}
        <div class="card shadow-sm">
            <div class="card-header fw-bold" style="background-color: #2E75B6; color: white;">
                <i class="bi bi-people me-2"></i>Ciudadanos Registrados
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        @forelse($ciudadanos as $usuario)
                        <tr>
                            <td class="fw-semibold"><i class="bi bi-person-circle me-1 text-muted"></i>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td><span class="badge bg-info text-dark">ciudadano</span></td>
                            <td>
                                @if($usuario->is_active)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Bloqueado</span>
                                @endif
                            </td>
                            <td class="d-flex gap-1 flex-wrap">
                                <a href="/usuarios/{{ $usuario->id }}/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
                                <form action="/usuarios/{{ $usuario->id }}/toggle" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-toggle-{{ $usuario->is_active ? 'on' : 'off' }} me-1"></i>{{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                                    </button>
                                </form>
                                <form action="/usuarios/{{ $usuario->id }}/delete" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No hay ciudadanos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
    </div>

    {{-- Modal de confirmación de eliminación --}}
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <div class="text-danger fs-1 w-100 text-center mt-2"><i class="bi bi-person-x-fill"></i></div>
                </div>
                <div class="modal-body text-center px-4 pb-0">
                    <h5 class="fw-bold mb-1">¿Eliminar usuario?</h5>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer. El usuario perderá acceso al sistema.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-2 pt-3">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger px-4" id="btnConfirmarEliminar">
                        <i class="bi bi-trash me-1"></i>Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var formEliminar = null;
        var modal = new bootstrap.Modal(document.getElementById('modalEliminar'));

        document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
            btn.addEventListener('click', function() {
                formEliminar = this.closest('form');
                modal.show();
            });
        });

        document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
            if (formEliminar) formEliminar.submit();
        });
    </script>
</body>
</html>
