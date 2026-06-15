<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Detalle del Reporte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/reportes" class="text-white text-decoration-none">Reportes</a>
                <a href="/mapa" class="text-white text-decoration-none">Mapa</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <a href="/reportes" class="btn btn-outline-secondary mb-3">← Volver</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="fw-bold mb-3" style="color: #1A3A5C;">{{ $reporte->titulo }}</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Categoría:</strong> {{ $reporte->categoria->nombre ?? 'Sin categoría' }}</p>
                        <p><strong>Descripción:</strong> {{ $reporte->descripcion }}</p>
                        <p><strong>Ubicación:</strong> {{ $reporte->latitud }}, {{ $reporte->longitud }}</p>
                        <p><strong>Fecha:</strong> {{ $reporte->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Estado actual:</strong>
                            @php
                                $colores = [
                                    'Pendiente'   => 'warning',
                                    'En revisión' => 'info',
                                    'En proceso'  => 'primary',
                                    'Resuelto'    => 'success',
                                    'Cerrado'     => 'secondary',
                                ];
                                $color = $colores[$reporte->estado] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $reporte->estado ?? 'Pendiente' }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        @if($reporte->foto_base64)
                            <img src="{{ $reporte->foto_base64 }}" class="img-fluid rounded" alt="Foto del reporte">
                        @else
                            <p class="text-muted">Sin fotografía</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMBIAR ESTADO - solo admin y supervisor --}}
        @if(auth()->user()->rol !== 'ciudadano')
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3" style="color: #1A3A5C;">Cambiar Estado</h5>
                <form action="/reportes/{{ $reporte->id }}/estado" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nuevo estado</label>
                        <select name="estado_nuevo" class="form-select" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En revisión">En revisión</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Resuelto">Resuelto</option>
                            <option value="Cerrado">Cerrado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentario (opcional)</label>
                        <textarea name="comentario" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn text-white" style="background-color: #1A3A5C;">Guardar cambio</button>
                </form>
            </div>
        </div>
        @endif

        {{-- HISTORIAL DE ESTADOS --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3" style="color: #1A3A5C;">Historial de Estados</h5>
                @forelse($estados as $estado)
                <div class="border-bottom pb-2 mb-2">
                    <span class="badge bg-secondary">{{ $estado->estado_anterior }}</span>
                    → <span class="badge bg-primary">{{ $estado->estado_nuevo }}</span>
                    <small class="text-muted ms-2">por {{ $estado->user->name }} — {{ $estado->created_at->format('d/m/Y H:i') }}</small>
                    @if($estado->comentario)
                        <p class="mb-0 mt-1 text-muted">{{ $estado->comentario }}</p>
                    @endif
                </div>
                @empty
                <p class="text-muted">Sin historial de cambios.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>