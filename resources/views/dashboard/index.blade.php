{{-- Dashboard con KPIs y gráficas Chart.js — Hecho por Keyra --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #F2F3F4; }
        .kpi-card { border-left: 4px solid #1A3A5C; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3 align-items-center">
                <a href="/mapa" class="text-white text-decoration-none"><i class="bi bi-map me-1"></i>Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none"><i class="bi bi-bar-chart me-1"></i>Dashboard</a>
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

    <div class="container-fluid py-4 px-4">

        {{-- 4 tarjetas KPI --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card kpi-card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Reportes</p>
                        <h2 class="fw-bold" style="color: #1A3A5C;">{{ $totalReportes }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm" style="border-left: 4px solid #27AE60;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Resueltos</p>
                        <h2 class="fw-bold text-success">{{ $resueltos }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm" style="border-left: 4px solid #E67E22;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Pendientes</p>
                        <h2 class="fw-bold text-warning">{{ $pendientes }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm" style="border-left: 4px solid #2E75B6;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Usuarios</p>
                        <h2 class="fw-bold text-primary">{{ $totalUsuarios }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3 gráficas Chart.js --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Reportes por Categoría</h6>
                        <canvas id="graficaPastel"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Reportes por Departamento</h6>
                        <canvas id="graficaBarras"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Tendencia Mensual {{ now()->year }}</h6>
                        <canvas id="graficaLineas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla últimos 10 reportes --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Últimos 10 Reportes</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #1A3A5C; color: white;">
                            <tr>
                                <th>ID</th><th>Título</th><th>Categoría</th><th>Departamento</th><th>Estado</th><th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosReportes as $reporte)
                                @php
                                    $coloresEstado = ['Pendiente'=>'warning','En revisión'=>'info','En proceso'=>'primary','Resuelto'=>'success','Cerrado'=>'secondary'];
                                    $colorEstado = $coloresEstado[$reporte->estado] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td><a href="/reportes/{{ $reporte->id }}" class="text-decoration-none">#{{ $reporte->id }}</a></td>
                                    <td>{{ $reporte->titulo }}</td>
                                    <td>{{ $reporte->categoria->nombre ?? '—' }}</td>
                                    <td>{{ $reporte->departamento ?? '—' }}</td>
                                    <td><span class="badge bg-{{ $colorEstado }}">{{ $reporte->estado }}</span></td>
                                    <td>{{ $reporte->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No hay reportes aún</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        // ── Gráfica 1: Pastel por categoría ──────────────────────────────────
        new Chart(document.getElementById('graficaPastel'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($categoriaLabels) !!},
                datasets: [{
                    data: {!! json_encode($categoriaData) !!},
                    backgroundColor: [
                        '#1A3A5C','#2E75B6','#27AE60','#E67E22','#E74C3C',
                        '#8E44AD','#16A085','#F39C12','#2C3E50','#D35400',
                        '#1ABC9C','#C0392B','#7F8C8D','#2980B9','#6C3483','#117A65'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
            }
        });

        // ── Gráfica 2: Barras por departamento ───────────────────────────────
        new Chart(document.getElementById('graficaBarras'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($departamentoLabels) !!},
                datasets: [{
                    label: 'Reportes',
                    data: {!! json_encode($departamentoData) !!},
                    backgroundColor: '#2E75B6',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // ── Gráfica 3: Líneas de tendencia mensual ───────────────────────────
        new Chart(document.getElementById('graficaLineas'), {
            type: 'line',
            data: {
                labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                datasets: [{
                    label: 'Reportes',
                    data: {!! json_encode($tendenciaMensual) !!},
                    borderColor: '#1A3A5C',
                    backgroundColor: 'rgba(26,58,92,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#1A3A5C'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
</body>
</html>
