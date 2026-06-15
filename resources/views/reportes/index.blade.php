<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Safe City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Safe City</a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text text-white me-3">{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Reportes</h5>
            @if(Auth::user()->rol === 'ciudadano')
                <a href="{{ route('reportes.create') }}" class="btn btn-light btn-sm">+ Nuevo Reporte</a>
            @endif
        </div>
        <div class="card-body">
            {{-- Filtros --}}
            <form method="GET" action="{{ route('reportes.index') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="departamento" class="form-select form-select-sm">
                        <option value="">Todos los departamentos</option>
                        @foreach(['La Paz','Cochabamba','Santa Cruz','Oruro','Potosí','Chuquisaca','Tarija','Beni','Pando'] as $dep)
                            <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gravedad" class="form-select form-select-sm">
                        <option value="">Toda gravedad</option>
                        <option value="leve" {{ request('gravedad') == 'leve' ? 'selected' : '' }}>Leve</option>
                        <option value="moderada" {{ request('gravedad') == 'moderada' ? 'selected' : '' }}>Moderada</option>
                        <option value="grave" {{ request('gravedad') == 'grave' ? 'selected' : '' }}>Grave</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todo estado</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                        <option value="resuelto" {{ request('estado') == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                    <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary btn-sm">✕</a>
                </div>
            </form>

            {{-- Tabla --}}
            @if($reportes->isEmpty())
                <p class="text-muted text-center py-3">No hay reportes para mostrar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Departamento</th>
                                <th>Gravedad</th>
                                <th>Estado</th>
                                <th>Reportado por</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportes as $reporte)
                            <tr>
                                <td>{{ $reporte->id }}</td>
                                <td>{{ $reporte->titulo }}</td>
                                <td>{{ $reporte->categoria->nombre ?? '-' }}</td>
                                <td>{{ $reporte->departamento ?? '-' }}</td>
                                <td>
                                    @php
                                        $gravBadge = ['leve' => 'success', 'moderada' => 'warning', 'grave' => 'danger'];
                                        $grav = $reporte->gravedad ?? 'leve';
                                    @endphp
                                    <span class="badge bg-{{ $gravBadge[$grav] ?? 'secondary' }}">{{ ucfirst($grav) }}</span>
                                </td>
                                <td>
                                    @php
                                        $estBadge = ['pendiente' => 'secondary', 'en_proceso' => 'info', 'resuelto' => 'success', 'rechazado' => 'danger'];
                                        $est = $reporte->estado ?? 'pendiente';
                                    @endphp
                                    <span class="badge bg-{{ $estBadge[$est] ?? 'secondary' }}">{{ ucwords(str_replace('_', ' ', $est)) }}</span>
                                </td>
                                <td>{{ $reporte->user->name ?? '-' }}</td>
                                <td>{{ $reporte->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('reportes.show', $reporte->id) }}" class="btn btn-outline-primary btn-sm">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
