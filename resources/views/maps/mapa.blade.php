<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Mapa nacional</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: calc(100vh - 56px);
        }

        #sidebar {
            width: 280px;
            min-width: 280px;
            background: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: auto;
        }

        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/mapa" class="text-white text-decoration-none">Mapa</a>
                <a href="/dashboard" class="text-white text-decoration-none">Dashboard</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar
                        sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="d-flex">
        <!-- SIDEBAR FILTROS -->
        <div id="sidebar">
            <h5 class="fw-bold mb-3" style="color: #1A3A5C;">Filtros</h5>
            <div class="mb-3">
                <label class="form-label">Departamento</label>
                <select class="form-select" id="filtroDepartamento">
                    <option value="">Todos</option>
                    <option>La Paz</option>
                    <option>Cochabamba</option>
                    <option>Santa Cruz</option>
                    <option>Oruro</option>
                    <option>Potosí</option>
                    <option>Chuquisaca</option>
                    <option>Tarija</option>
                    <option>Beni</option>
                    <option>Pando</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" id="filtroCategoria">
                    <option value="">Todas</option>
                    <option>Robo</option>
                    <option>Vandalismo</option>
                    <option>Accidente</option>
                    <option>Bache</option>
                    <option>Basura</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Gravedad</label>
                <select class="form-select" id="filtroGravedad">
                    <option value="">Todas</option>
                    <option>Baja</option>
                    <option>Media</option>
                    <option>Alta</option>
                    <option>Crítica</option>
                </select>
            </div>
            <button class="btn w-100 mb-2 text-white" style="background-color: #1A3A5C;">Aplicar filtros</button>
            <button class="btn w-100" style="border: 2px solid #1A3A5C; color: #1A3A5C;">Ver Heatmap</button>
        </div>

        <!-- MAPA -->
        <div id="map" class="flex-grow-1"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-16.5, -64.0], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marcadores de ejemplo
        var marcadores = [];

        fetch('/mapa/reportes')
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.length === 0) {
                    // Si no hay reportes reales, mostrar ejemplos
                    var ejemplos = [
                        { lat: -16.5, lng: -68.15, titulo: 'Ejemplo La Paz', categoria: 'Robo' },
                        { lat: -17.39, lng: -66.16, titulo: 'Ejemplo Cochabamba', categoria: 'Accidente' },
                        { lat: -17.78, lng: -63.18, titulo: 'Ejemplo Santa Cruz', categoria: 'Vandalismo' },
                    ];
                    ejemplos.forEach(function (m) {
                        var marker = L.marker([m.lat, m.lng])
                            .addTo(map)
                            .bindPopup('<b>' + m.titulo + '</b><br>Categoría: ' + m.categoria);
                        marker.categoria = m.categoria;
                        marcadores.push(marker);
                    });
                } else {
                    data.forEach(function (m) {
                        var marker = L.marker([m.lat, m.lng])
                            .addTo(map)
                            .bindPopup('<b>' + m.titulo + '</b><br>Categoría: ' + m.categoria);
                        marker.categoria = m.categoria;
                        marcadores.push(marker);
                    });
                }
            });
    </script>
</body>

</html>