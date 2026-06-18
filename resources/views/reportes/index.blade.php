<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Reportes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3 align-items-center">
                <a href="/mapa" class="text-white text-decoration-none"><i class="bi bi-map me-1"></i>Mapa</a>
                {{-- Dashboard solo para admin y supervisor --}}
                @if(auth()->user()->rol !== 'ciudadano')
                    <a href="/dashboard" class="text-white text-decoration-none"><i class="bi bi-bar-chart me-1"></i>Dashboard</a>
                @endif
                <a href="/reportes" class="text-white text-decoration-none"><i class="bi bi-file-earmark-text me-1"></i>Reportes</a>
                @if(auth()->user()->rol === 'administrador')
                    <a href="/usuarios"   class="text-white text-decoration-none"><i class="bi bi-people me-1"></i>Usuarios</a>
                    <a href="/categorias" class="text-white text-decoration-none"><i class="bi bi-tags me-1"></i>Categorías</a>
                @endif
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold" style="color: #1A3A5C;"><i class="bi bi-file-earmark-text me-2"></i>Reportes de Incidencias</h4>
            <a href="/reportes/crear" class="btn text-white" style="background-color: #1A3A5C;">
                <i class="bi bi-plus-circle me-1"></i>Nuevo Reporte
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif

        {{-- Filtros de búsqueda --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="/reportes" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small mb-1"><i class="bi bi-search me-1"></i>Buscar por título</label>
                        <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Ej: Bache..." value="{{ $buscar ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1"><i class="bi bi-tags me-1"></i>Categoría</label>
                        <select name="categoria_id" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ ($filtroCat ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1"><i class="bi bi-circle-half me-1"></i>Estado</label>
                        <select name="estado" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            @foreach(['Pendiente','En revisión','En proceso','Resuelto','Cerrado'] as $est)
                                <option value="{{ $est }}" {{ ($filtroEst ?? '') === $est ? 'selected' : '' }}>{{ $est }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm text-white" style="background-color: #1A3A5C;"><i class="bi bi-search me-1"></i>Filtrar</button>
                        <a href="/reportes" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Limpiar</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead style="background-color: #1A3A5C; color: white;">
                        <tr>
                            <th>Título</th><th>Categoría</th><th>Departamento</th>
                            <th>Gravedad</th><th>Estado</th><th>Fecha</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportes as $reporte)
                        @php
                            $grav = $reporte->gravedad ?? 'Media';
                            $gc   = ['Baja'=>'success','Media'=>'warning','Alta'=>'danger','Crítica'=>'dark'][$grav] ?? 'secondary';
                            $colores = ['Pendiente'=>'warning','En revisión'=>'info','En proceso'=>'primary','Resuelto'=>'success','Cerrado'=>'secondary'];
                            $color = $colores[$reporte->estado] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $reporte->titulo }}</td>
                            <td>{{ $reporte->categoria->nombre ?? '—' }}</td>
                            <td><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $reporte->departamento ?? '—' }}</td>
                            <td><span class="badge bg-{{ $gc }}">{{ $grav }}</span></td>
                            <td><span class="badge bg-{{ $color }}">{{ $reporte->estado }}</span></td>
                            <td>{{ $reporte->created_at->format('d/m/Y') }}</td>
                            <td class="d-flex gap-1">
                                <a href="/reportes/{{ $reporte->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Ver</a>
                                {{-- Editar solo si es el dueño y está Pendiente --}}
                                @if(auth()->id() === $reporte->user_id && $reporte->estado === 'Pendiente')
                                    <a href="/reportes/{{ $reporte->id }}/edit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Editar</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay reportes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $reportes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</body>
</html>
