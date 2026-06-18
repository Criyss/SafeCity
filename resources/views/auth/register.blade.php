<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #EBF5FB; }
        .brand-header { background-color: #1A3A5C; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-5 col-sm-10">
        <div class="card shadow">
            <div class="card-header brand-header text-white text-center py-3">
                <h4 class="mb-0 fw-bold">🛡️ Safe City</h4>
                <small class="opacity-75">Registro de Ciudadano</small>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre completo</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn w-100 text-white fw-bold" style="background-color: #1A3A5C;">
                        Crear cuenta
                    </button>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <small>¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color: #1A3A5C;">Inicia sesión aquí</a></small>
            </div>
        </div>
    </div>
</body>
</html>
