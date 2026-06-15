<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Reporte #{{ $reporte->id }} - Safe City</title>
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
    <div class="mb-3">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary btn-sm">← Volver al listado</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        {{-- Detalle principal --}}
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Reporte #{{ $reporte->id }}: {{ $reporte->titulo }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted fw-semibold">Categoría</div>
                        <div class="col-sm-8">{{ $reporte->categoria->nombre ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted fw-semibold">Departamento</div>
                        <div class="col-sm-8">{{ $reporte->departamento ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted fw-semibold">Gravedad</div>
                        <div class="col-sm-8">
                            @php $gravBadge = ['leve' => 'success', 'moderada' => 'warning', 'grave' => 'danger']; $grav = $reporte->gravedad ?? 'leve'; @endphp
                            <span class="badge bg-{{ $gravBadge[$grav] ?? 'secondary' }}">{{ ucfirst($grav) }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted fw-semibold">Estado actual</div>
                        <div class="col-sm-8">
                            @php $estBadge = ['pendiente' => 'secondary', 'en_proceso' => 'info', 'resuelto' => 'success', 'rechazado' => 'danger']; $est = $reporte->estado ?? 'pendiente'; @endphp
                            <span class="badge bg-{{ $estBadge[$est] ?? 'secondary' }}">{{ ucwords(str_replace('_', ' ', $est)) }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted fw-semibold">Fecha incidente</div>
                        <div class="col-sm-8">{{ $reporte->fecha_incidente ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted fw-semibold">Reportado por</div>
                        <div class="col-sm-8">{{ $reporte->user->name ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted fw-semibold">Coordenadas</div>
                        <div class="col-sm-8">{{ $reporte->latitud }}, {{ $reporte->longitud }}</div>
                    </div>
                    <hr>
                    <div class="mb-2 text-muted fw-semibold">Descripción</div>
                    <p>{{ $reporte->descripcion }}</p>

                    @if($reporte->foto_base64)
                        <hr>
                        <div class="mb-2 text-muted fw-semibold">Evidencia fotográfica</div>
                        <img src="{{ $reporte->foto_base64 }}" class="img-fluid rounded" style="max-height:350px;" alt="Evidencia">
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel derecho: cambio de estado + historial --}}
        <div class="col-md-4">

            {{-- Cambio de estado: solo admin y supervisor --}}
            @if(in_array(Auth::user()->rol, ['administrador', 'supervisor']))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">Cambiar Estado</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('reportes.cambiarEstado', $reporte->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nuevo estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En proceso</option>
                                <option value="resuelto">Resuelto</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comentario (opcional)</label>
                            <textarea name="comentario" class="form-control" rows="3" placeholder="Observación..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Actualizar estado</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Historial de estados --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Historial de estados</h6>
                </div>
                <div class="card-body p-0">
                    @if($reporte->estadosReporte->isEmpty())
                        <p class="text-muted text-center py-3 mb-0">Sin historial aún.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($reporte->estadosReporte()->with('user')->latest()->get() as $historial)
                            <li class="list-group-item">
                                @php $estBadge2 = ['pendiente' => 'secondary', 'en_proceso' => 'info', 'resuelto' => 'success', 'rechazado' => 'danger']; @endphp
                                <span class="badge bg-{{ $estBadge2[$historial->estado] ?? 'secondary' }} me-1">
                                    {{ ucwords(str_replace('_', ' ', $historial->estado)) }}
                                </span>
                                <small class="text-muted">{{ $historial->created_at->format('d/m/Y H:i') }}</small>
                                <br>
                                <small class="text-muted">Por: {{ $historial->user->name ?? '-' }}</small>
                                @if($historial->comentario)
                                    <p class="mb-0 mt-1 small">{{ $historial->comentario }}</p>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
