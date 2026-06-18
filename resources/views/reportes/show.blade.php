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
            <div class="d-flex gap-3">
                <a href="/reportes" class="text-white text-decoration-none">Reportes</a>
                <a href="/mapa"     class="text-white text-decoration-none">Mapa</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <a href="/reportes" class="btn btn-outline-secondary mb-3">← Volver</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
                            <div class="p-4 text-center text-muted border rounded">Sin fotografía</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario de cambio de estado — solo admin y supervisor lo ven --}}
        @if(auth()->user()->rol !== 'ciudadano')
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background-color: #EBF5FB; border-left: 4px solid #1A3A5C;">
                <h6 class="mb-0 fw-bold" style="color: #1A3A5C;">Cambiar Estado</h6>
            </div>
            <div class="card-body">
                {{-- @csrf protege el formulario contra ataques CSRF --}}
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