<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Mi Perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3 align-items-center">
                <a href="/mapa" class="text-white text-decoration-none"><i class="bi bi-map me-1"></i>Mapa</a>
                @if(auth()->user()->rol !== 'ciudadano')
                    <a href="/dashboard" class="text-white text-decoration-none"><i class="bi bi-bar-chart me-1"></i>Dashboard</a>
                @endif
                <a href="/reportes" class="text-white text-decoration-none"><i class="bi bi-file-earmark-text me-1"></i>Reportes</a>
                @if(auth()->user()->rol === 'administrador')
                    <a href="/usuarios"   class="text-white text-decoration-none"><i class="bi bi-people me-1"></i>Usuarios</a>
                    <a href="/categorias" class="text-white text-decoration-none"><i class="bi bi-tags me-1"></i>Categorías</a>
                @endif
                <a href="/perfil" class="text-white text-decoration-none"><i class="bi bi-person-circle me-1"></i>Mi Perfil</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 700px;">
        <h4 class="fw-bold mb-4" style="color: #1A3A5C;"><i class="bi bi-person-circle me-2"></i>Mi Perfil</h4>

        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif

        {{-- Tarjetas de estadísticas --}}
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold" style="color: #1A3A5C;">{{ $totalReportes }}</div>
                        <div class="small text-muted">Reportes enviados</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-success">{{ $resueltos }}</div>
                        <div class="small text-muted">Resueltos</div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-warning">{{ $pendientes }}</div>
                        <div class="small text-muted">Pendientes</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de perfil --}}
        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold" style="background-color: #1A3A5C; color: white;">
                <i class="bi bi-pencil-square me-2"></i>Editar información
            </div>
            <div class="card-body p-4">
                <form action="/perfil/update" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-person me-1"></i>Nombre</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-envelope me-1"></i>Correo electrónico</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->email }}" disabled>
                        <div class="form-text">El correo no se puede cambiar.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-shield me-1"></i>Rol</label>
                        <input type="text" class="form-control bg-light text-capitalize" value="{{ $user->rol }}" disabled>
                    </div>

                    <hr class="my-4">
                    <p class="fw-semibold mb-3"><i class="bi bi-lock me-1"></i>Cambiar contraseña <span class="text-muted fw-normal small">(dejar en blanco para no cambiar)</span></p>

                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva contraseña">
                    </div>

                    <button type="submit" class="btn text-white px-4" style="background-color: #1A3A5C;">
                        <i class="bi bi-check-circle me-1"></i>Guardar cambios
                    </button>
                    <a href="/reportes" class="btn btn-outline-secondary ms-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
