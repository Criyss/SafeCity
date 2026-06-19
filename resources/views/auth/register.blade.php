<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1A3A5C 0%, #2E75B6 100%); min-height: 100vh; }
        .card { border: none; border-radius: 16px; }
        .brand-header { background-color: #1A3A5C; border-radius: 16px 16px 0 0; }
        .form-control:focus { border-color: #1A3A5C; box-shadow: 0 0 0 0.2rem rgba(26,58,92,.2); }
        .strength-bar { height: 4px; border-radius: 2px; transition: width .3s, background .3s; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="col-md-5 col-sm-10 col-11">
        <div class="card shadow-lg">
            <div class="card-header brand-header text-white text-center py-4">
                <i class="bi bi-shield-fill-check fs-1 d-block mb-2"></i>
                <h4 class="mb-0 fw-bold">Safe City</h4>
                <small class="opacity-75">Registro de Ciudadano</small>
            </div>
            <div class="card-body p-4">

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

                <form action="{{ route('register.post') }}" method="POST" novalidate id="registerForm">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Nombre completo
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Ej: Ana García López"
                            required
                            autofocus
                            autocomplete="name"
                            maxlength="255"
                            minlength="3"
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Correo --}}
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
                            autocomplete="email"
                            maxlength="255"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contraseña + ojo + barra de fuerza --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Mínimo 8 caracteres"
                                required
                                autocomplete="new-password"
                                minlength="8"
                            >
                            <button type="button" class="btn btn-outline-secondary" id="togglePw1" tabindex="-1" title="Mostrar/ocultar">
                                <i class="bi bi-eye" id="eye1"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Barra de fuerza de contraseña --}}
                        <div class="mt-2">
                            <div class="bg-light rounded" style="height:4px;">
                                <div class="strength-bar" id="strengthBar" style="width:0%;background:#dc3545;"></div>
                            </div>
                            <small id="strengthText" class="text-muted"></small>
                        </div>
                    </div>

                    {{-- Confirmar contraseña + ojo --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-lock-fill me-1"></i>Confirmar contraseña
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Repite la contraseña"
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="btn btn-outline-secondary" id="togglePw2" tabindex="-1" title="Mostrar/ocultar">
                                <i class="bi bi-eye" id="eye2"></i>
                            </button>
                        </div>
                        <div id="matchMsg" class="form-text mt-1"></div>
                    </div>

                    <button type="submit" class="btn w-100 text-white fw-bold py-2" style="background-color: #1A3A5C;">
                        <i class="bi bi-person-plus me-2"></i>Crear cuenta
                    </button>
                </form>
            </div>
            <div class="card-footer text-center py-3 bg-white" style="border-radius: 0 0 16px 16px;">
                <small>¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" style="color: #1A3A5C;" class="fw-semibold">Inicia sesión aquí</a>
                </small>
            </div>
        </div>
    </div>

    <script>
        // ── Ojos mostrar/ocultar ─────────────────────────────────────────
        function togglePw(btnId, inputId, iconId) {
            document.getElementById(btnId).addEventListener('click', function() {
                const input = document.getElementById(inputId);
                const icon  = document.getElementById(iconId);
                if (input.type === 'password') {
                    input.type  = 'text';
                    icon.className = 'bi bi-eye-slash';
                } else {
                    input.type  = 'password';
                    icon.className = 'bi bi-eye';
                }
            });
        }
        togglePw('togglePw1', 'password', 'eye1');
        togglePw('togglePw2', 'password_confirmation', 'eye2');

        // ── Barra de fuerza de contraseña ────────────────────────────────
        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const bar = document.getElementById('strengthBar');
            const txt = document.getElementById('strengthText');
            let score = 0;
            if (val.length >= 8)  score++;
            if (val.length >= 12) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [
                { w:'0%',   c:'#dc3545', t:'' },
                { w:'25%',  c:'#dc3545', t:'Muy débil' },
                { w:'50%',  c:'#fd7e14', t:'Débil' },
                { w:'70%',  c:'#ffc107', t:'Regular' },
                { w:'90%',  c:'#198754', t:'Fuerte' },
                { w:'100%', c:'#0d6efd', t:'Muy fuerte' },
            ];
            const l = levels[Math.min(score, 5)];
            bar.style.width      = l.w;
            bar.style.background = l.c;
            txt.textContent      = l.t;
            txt.style.color      = l.c;

            // verificar coincidencia en tiempo real
            checkMatch();
        });

        // ── Verificar que las contraseñas coincidan ───────────────────────
        function checkMatch() {
            const pw1 = document.getElementById('password').value;
            const pw2 = document.getElementById('password_confirmation').value;
            const msg = document.getElementById('matchMsg');
            const inp = document.getElementById('password_confirmation');
            if (!pw2) { msg.textContent = ''; return; }
            if (pw1 === pw2) {
                msg.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i><span class="text-success">Las contraseñas coinciden.</span>';
                inp.classList.remove('is-invalid');
                inp.classList.add('is-valid');
            } else {
                msg.innerHTML = '<i class="bi bi-x-circle-fill text-danger me-1"></i><span class="text-danger">Las contraseñas no coinciden.</span>';
                inp.classList.remove('is-valid');
                inp.classList.add('is-invalid');
            }
        }
        document.getElementById('password_confirmation').addEventListener('input', checkMatch);

        // ── Validación completa al enviar ────────────────────────────────
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const name  = document.getElementById('name');
            const email = document.getElementById('email');
            const pw    = document.getElementById('password');
            const pw2   = document.getElementById('password_confirmation');
            let valid   = true;

            // Nombre
            if (!name.value.trim() || name.value.trim().length < 3) {
                name.classList.add('is-invalid'); valid = false;
            } else { name.classList.remove('is-invalid'); }

            // Email
            if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                email.classList.add('is-invalid'); valid = false;
            } else { email.classList.remove('is-invalid'); }

            // Contraseña
            if (!pw.value || pw.value.length < 8) {
                pw.classList.add('is-invalid'); valid = false;
            } else { pw.classList.remove('is-invalid'); }

            // Confirmación
            if (pw.value !== pw2.value || !pw2.value) {
                pw2.classList.add('is-invalid'); valid = false;
            } else { pw2.classList.remove('is-invalid'); }

            if (!valid) e.preventDefault();
        });

        // Limpiar al escribir
        ['name','email','password','password_confirmation'].forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    </script>
</body>
</html>
