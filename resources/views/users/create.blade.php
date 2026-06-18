<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Nuevo Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3 align-items-center">
                <a href="/usuarios" class="text-white text-decoration-none"><i class="bi bi-people me-1"></i>Usuarios</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container py-4" style="max-width: 600px;">
        <h4 class="fw-bold mb-4" style="color: #1A3A5C;"><i class="bi bi-person-plus me-2"></i>Nuevo Usuario</h4>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)<div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>@endforeach
            </div>
        @endif
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="/usuarios" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-person me-1"></i>Nombre completo</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-envelope me-1"></i>Correo electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-lock me-1"></i>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="bi bi-person-badge me-1"></i>Rol</label>
                        <select name="rol" class="form-select" required>
                            <option value="">Seleccionar rol</option>
                            <option value="administrador" {{ old('rol')=='administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="supervisor"    {{ old('rol')=='supervisor'    ? 'selected' : '' }}>Supervisor</option>
                            <option value="ciudadano"     {{ old('rol')=='ciudadano'     ? 'selected' : '' }}>Ciudadano</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white w-100" style="background-color: #1A3A5C;"><i class="bi bi-save me-1"></i>Guardar</button>
                        <a href="/usuarios" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i>Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
