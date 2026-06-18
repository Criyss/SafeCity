{{-- Mapa interno con marcadores y heatmap — Hecho por Keyra --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Mapa Nacional</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    {{-- Leaflet CSS: estilos del mapa --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #map     { height: calc(100vh - 56px); }
        #sidebar { width: 270px; min-width: 270px; background: white; box-shadow: 2px 0 5px rgba(0,0,0,0.1); padding: 20px; overflow-y: auto; max-height: calc(100vh - 56px); }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
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
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        {{-- Sidebar de filtros --}}
        <div id="sidebar">
            <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Filtros del mapa</h6>

            {{-- Categorías cargadas dinámicamente desde la BD --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold">Categoría</label>
                <select class="form-select form-select-sm" id="filtroCategoria">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Departamento</label>
                <select class="form-select form-select-sm" id="filtroDepartamento">
                    <option value="">Todos</option>
                    @foreach(['La Paz','Cochabamba','Santa Cruz','Oruro','Potosí','Chuquisaca','Tarija','Beni','Pando'] as $dep)
                        <option value="{{ $dep }}">{{ $dep }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Estado</label>
                <select class="form-select form-select-sm" id="filtroEstado">
                    <option value="">Todos</option>
                    <option>Pendiente</option>
                    <option>En revisión</option>
                    <option>En proceso</option>
                    <option>Resuelto</option>
                    <option>Cerrado</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Gravedad</label>
                <select class="form-select form-select-sm" id="filtroGravedad">
                    <option value="">Todas</option>
                    <option>Baja</option>
                    <option>Media</option>
                    <option>Alta</option>
                    <option>Crítica</option>
                </select>
            </div>

            <button onclick="aplicarFiltros()" class="btn w-100 btn-sm text-white mb-2" style="background-color: #1A3A5C;">Aplicar filtros</button>
            <button onclick="limpiarFiltros()" class="btn w-100 btn-sm mb-3" style="border: 1px solid #1A3A5C; color: #1A3A5C;">Limpiar</button>

            <div id="contador" class="text-muted small text-center mb-2"></div>

            <hr>
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" id="toggleHeatmap" checked>
                <label for="toggleHeatmap" class="small mb-0">Mostrar heatmap</label>
            </div>

            {{-- Leyenda de colores por gravedad --}}
            <hr>
            <p class="small fw-semibold mb-1">Leyenda:</p>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#27AE60;"></div><small>Baja</small></div>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#F39C12;"></div><small>Media</small></div>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#E67E22;"></div><small>Alta</small></div>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#C0392B;"></div><small>Crítica</small></div>
        </div>

        <div id="map" class="flex-grow-1"></div>
    </div>

    {{-- Leaflet JS + plugin heatmap --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <script>
        // Inicializar mapa centrado en Bolivia (sin API