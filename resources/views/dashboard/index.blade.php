<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #F2F3F4; }
        .kpi-card { border-left: 4px solid #1A3A5C; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/mapa" class="text-white text-decoration-none">Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none">Dashboard</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">

        <!-- KPI CARDS -->
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

        <!-- GRAFICAS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Reportes por Categoría</h6>
                        <canvas id="graficaPastel"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Reportes por Departamento</h6>
                        <canvas id="graficaBarras"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Tendencia Mensual</h6>
                        <canvas id="graficaLineas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ULTIMOS REPORTES -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Últimos 10 Reportes</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #1A3A5C; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Categoría</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosReportes as $reporte)
                            <tr>
                                <td>#{{ $reporte->id }}</td>
                                <td>{{ $reporte->titulo }}</td>
                                <td>{{ $reporte->categoria->nombre ?? 'Sin categoría' }}</td>
                                <td>{{ $reporte->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay reportes aún</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gráfica de pastel
        new Chart(document.getElementById('graficaPastel'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($categoriaLabels) !!},
                datasets: [{
                    data: {!! json_encode($categoriaData) !!},
                    backgroundColor: ['#1A3A5C','#2E75B6','#27AE60','#E67E22','#C0392B','#7D3C98','#F39C12','#16A085']
                }]
            }
        });

        // Gráfica de barras
        new Chart(document.getElementById('graficaBarras'), {
            type: 'bar',
            data: {
                labels: ['La Paz','Cbba','Sta. Cruz','Oruro','Potosí','Chuquisaca','Tarija','Beni','Pando'],
                datasets: [{
                    label: 'Reportes',
                    data: {!! json_encode($departamentoData) !!},
                    backgroundColor: '#2E75B6'
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });

        // Gráfica de líneas
        new Chart(document.getElementById('graficaLineas'), {
            type: 'line',
            data: {
                labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                datasets: [{
                    label: 'Reportes',
                    data: {!! json_encode($mensualData) !!},
                    borderColor: '#1A3A5C',
                    tension: 0.3,
                    fill: false
                }]
            }
        });
    </script>
</body>
</html>