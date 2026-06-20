{{-- Detalle del reporte. Cambio de estado + historial: Keyra --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Detalle del Reporte</title>
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

    <div class="container py-4">
        <a href="/reportes" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Volver</a>

        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif

        {{-- Datos del reporte --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1A3A5C;">
                <h5 class="mb-0">{{ $reporte->titulo }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Categoría:</strong> {{ $reporte->categoria->nombre ?? '—' }}</p>
                        <p><strong>Departamento:</strong> {{ $reporte->departamento ?? '—' }}</p>
                        <p>
                            <strong>Gravedad:</strong>
                            @php
                                $colorG = ['Baja'=>'success','Media'=>'warning','Alta'=>'danger','Crítica'=>'dark'][$reporte->gravedad ?? 'Media'] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $colorG }}">{{ $reporte->gravedad ?? '—' }}</span>
                        </p>
                        <p><strong>Descripción:</strong> {{ $reporte->descripcion }}</p>
                        <p><strong>GPS:</strong> {{ $reporte->latitud }}, {{ $reporte->longitud }}</p>
                        <p><strong>Reportado por:</strong> {{ $reporte->user->name ?? '—' }}</p>
                        <p><strong>Fecha:</strong> {{ $reporte->created_at->format('d/m/Y H:i') }}</p>
                        <p>
                            <strong>Estado:</strong>
                            @php
                                $colorE = ['Pendiente'=>'warning','En revisión'=>'info','En proceso'=>'primary','Resuelto'=>'success','Cerrado'=>'secondary'][$reporte->estado] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $colorE }}">{{ $reporte->estado }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        @if($reporte->foto_base64)
                            <img src="{{ $reporte->foto_base64 }}" class="img-fluid rounded shadow-sm" alt="Foto de evidencia">
                        @else
                            <div class="p-4 text-center text-muted border rounded">
                                <i class="bi bi-image fs-2 d-block mb-2"></i>Sin fotografía
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de cambio de estado — solo admin y supervisor lo ven --}}
        @if(auth()->user()->rol !== 'ciudadano')
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background-color: #EBF5FB; border-left: 4px solid #1A3A5C;">
                <h6 class="mb-0 fw-bold" style="color: #1A3A5C;"><i class="bi bi-arrow-repeat me-2"></i>Cambiar Estado</h6>
            </div>
            <div class="card-body">
                <form action="/reportes/{{ $reporte->id }}/estado" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nuevo Estado</label>
                        <select name="estado_nuevo" class="form-select" required>
                            <option value="Pendiente"   {{ $reporte->estado === 'Pendiente'   ? 'selected' : '' }}>Pendiente</option>
                            <option value="En revisión" {{ $reporte->estado === 'En revisión' ? 'selected' : '' }}>En revisión</option>
                            <option value="En proceso"  {{ $reporte->estado === 'En proceso'  ? 'selected' : '' }}>En proceso</option>
                            <option value="Resuelto"    {{ $reporte->estado === 'Resuelto'    ? 'selected' : '' }}>Resuelto</option>
                            <option value="Cerrado"     {{ $reporte->estado === 'Cerrado'     ? 'selected' : '' }}>Cerrado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Comentario (opcional)</label>
                        <textarea name="comentario" class="form-control" rows="2" placeholder="Ej: Se envió al equipo de mantenimiento..."></textarea>
                    </div>
                    <button type="submit" class="btn text-white" style="background-color: #1A3A5C;">
                        <i class="bi bi-save me-1"></i>Guardar cambio
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Historial de estados --}}
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: #EBF5FB; border-left: 4px solid #1A3A5C;">
                <h6 class="mb-0 fw-bold" style="color: #1A3A5C;"><i class="bi bi-clock-history me-2"></i>Historial de Estados</h6>
            </div>
            <div class="card-body">
                @forelse($estados as $est)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="text-center" style="min-width:40px;">
                            <i class="bi bi-circle-fill" style="color: #1A3A5C;"></i>
                        </div>
                        <div>
                            <p class="mb-1">
                                <span class="fw-semibold">{{ $est->user->name ?? '—' }}</span>
                                cambió de
                                <span class="badge bg-secondary">{{ $est->estado_anterior }}</span>
                                a
                                @php
                                    $ce = ['Pendiente'=>'warning','En revisión'=>'info','En proceso'=>'primary','Resuelto'=>'success','Cerrado'=>'secondary'][$est->estado_nuevo] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $ce }}">{{ $est->estado_nuevo }}</span>
                            </p>
                            @if($est->comentario)
                                <p class="text-muted small mb-1"><i class="bi bi-chat-left-text me-1"></i>{{ $est->comentario }}</p>
                            @endif
                            <small class="text-muted"><i class="bi bi-calendar me-1"></i>{{ $est->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3"><i class="bi bi-inbox me-2"></i>Sin historial aún.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
