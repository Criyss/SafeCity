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
        .form-control:focus { border-color: #1A3A5C; box-shadow: 0 0 0 0.2rem rgba(26,58,92,.2); }
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
                    <div class="alert alert-success d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" novalidate id="loginForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1"></i>Correo electrónico
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="ejemplo@correo.com"
                            required
                            autofocus
                            autocomplete="email"
                            maxlength="255"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Tu contraseña"
                                required
                                autocomplete="current-password"
                                minlength="6"
                            >
                            <button type="button" class="btn btn-outline-secondary" id="togglePw" tabindex="-1" title="Mostrar/ocultar contraseña">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text text-muted mt-1">
                            <i class="bi bi-info-circle me-1"></i>Mínimo 6 caracteres.
                        </div>
                    </div>

                    <button type="submit" class="btn w-100 text-white fw-bold py-2" style="background-color: #1A3A5C;">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                    </button>
                </form>
            </div>
            <div class="card-footer text-center py-3 bg-white" style="border-radius: 0 0 16px 16px;">
                <small>¿No tienes cuenta?
                    <a href="{{ route('register') }}" style="color: #1A3A5C;" class="fw-semibold">Regístrate aquí</a>
                </small>
            </div>
        </div>
    </div>

    <script>
        // Ojo mostrar/ocultar contraseña
        document.getElementById('togglePw').addEventListener('click', function() {
            const pw  = document.getElementById('password');
            const ico = document.getElementById('eyeIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                ico.className = 'bi bi-eye-slash';
            } else {
                pw.type = 'password';
                ico.className = 'bi bi-eye';
            }
        });

        // Validación antes de enviar
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const pw    = document.getElementById('password');
            let valid   = true;

            if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                email.classList.add('is-invalid');
                if (!email.nextElementSibling || !email.nextElementSibling.classList.contains('invalid-feedback')) {
                    const msg = document.createElement('div');
                    msg.className = 'invalid-feedback';
                    msg.textContent = 'Ingresa un correo electrónico válido.';
                    email.after(msg);
                }
                valid = false;
            } else {
                email.classList.remove('is-invalid');
            }

            if (!pw.value || pw.value.length < 6) {
                pw.classList.add('is-invalid');
                valid = false;
            } else {
                pw.classList.remove('is-invalid');
            }

            if (!valid) e.preventDefault();
        });

        // Limpiar al escribir
        ['email','password'].forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    </script>
</body>
</html>
