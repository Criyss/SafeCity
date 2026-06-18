<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1A3A5C 0%, #2E75B6 100%); min-height: 100vh; }
        .card { border: none; border-radius: 16px; }
        .brand-header { background-color: #1A3A5C; border-radius: 16px 16px 0 0; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-4 col-sm-10 col-11">
        <div class="card shadow-lg">
            <div class="card-header brand-header text-white text-center py-4">
                <i class="bi bi-shield-fill-check fs-1 d-block mb-2"></i>
                <h4 class="mb-0 fw-bold">Safe City</h4>
                <small class="opacity-75">Plataforma de Incidencias Urbanas</small>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-envelope me-1"></i>Correo electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="bi bi-lock me-1"></i>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn w-100 text-white fw-bold py-2" style="background-color: #1A3A5C;">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                    </button>
                </form>
            </div>
            <div class="card-footer text-center py-3 bg-white" style="border-radius: 0 0 16px 16px;">
                <small>¿No tienes cuenta? <a href="{{ route('register') }}" style="color: #1A3A5C;" class="fw-semibold">Regístrate aquí</a></small>
            </div>
        </div>
    </div>
</body>
</html>
