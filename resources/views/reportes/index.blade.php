<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Reportes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/mapa" class="text-white text-decoration-none">Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none">Dashboard</a>
                <a href="/reportes" class="text-white text-decoration-none">Reportes</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold" style="color: #1A3A5C;">Reportes de Incidencias</h4>
            <a href="/reportes/crear" class="btn text-white" style="background-color: #1A3A5C;">+ Nuevo Reporte</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
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
                        @endphp
                        <tr>
                            <td>{{ $reporte->titulo }}</td>
                            <td>{{ $reporte->categoria->nombre ?? 'Sin categoría' }}</td>
                            <td>{{ $reporte->departamento ?? '—' }}</td>
                            <td><span class="badge bg-{{ $gc }}">{{ $grav }}</span></td>
                            <td>
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
                            </td>
                            <td>{{ $reporte->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="/reportes/{{ $reporte->id }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay reportes aún.</td>
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