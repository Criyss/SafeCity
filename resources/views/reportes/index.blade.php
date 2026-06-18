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
                <a href="/mapa"      class="text-white text-decoration-none"><i class="bi bi-map me-1"></i>Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none"><i class="bi bi-bar-chart me-1"></i>Dashboard</a>
                <a href="/reportes"  class="text-white text-decoration-none"><i class="bi bi-file-earmark-text me-1"></i>Reportes</a>
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

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead style="background-color: #1A3A5C; color: white;">
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Departamento</th>
                            <th>Gravedad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportes as $reporte)
                        @php
                            $grav = $reporte->gravedad ?? 'Media';
                            $gc = ['Baja'=>'success','Media'=>'warning','Alta'=>'danger','Crítica'=>'dark'][$grav] ?? 'secondary';
                            $colores = ['Pendiente'=>'warning','En revisión'=>'info','En proceso'=>'primary','Resuelto'=>'success','Cerrado'=>'secondary'];
                            $color = $colores[$reporte->estado] ?? 'secondary';
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $reporte->titulo }}</td>
                            <td>{{ $reporte->categoria->nombre ?? 'Sin categoría' }}</td>
                            <td><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $reporte->departamento ?? '—' }}</td>
                            <td><span class="badge bg-{{ $gc }}">{{ $grav }}</span></td>
                            <td><span class="badge bg-{{ $color }}">{{ $reporte->estado ?? 'Pendiente' }}</span></td>
                            <td>{{ $reporte->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="/reportes/{{ $reporte->id }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay reportes aún.
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
