<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Mapa nacional</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: calc(100vh - 56px); }
        #sidebar {
            width: 280px;
            min-width: 280px;
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding: 20px;
            overflow-y: auto;
        }
    </style>
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

    <div class="d-flex">
        <div id="sidebar">
            <h5 class="fw-bold mb-3" style="color: #1A3A5C;">Filtros</h5>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" id="filtroCategoria">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->nombre }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" id="filtroEstado">
                    <option value="">Todos</option>
                    <option>Pendiente</option>
                    <option>En revisión</option>
                    <option>En proceso</option>
                    <option>Resuelto</option>
                    <option>Cerrado</option>
                </select>
            </div>
            <button id="btnFiltrar" class="btn w-100 mb-2 text-white" style="background-color: #1A3A5C;">Aplicar filtros</button>
            <button id="btnLimpiar" class="btn w-100" style="border: 2px solid #1A3A5C; color: #1A3A5C;">Limpiar filtros</button>
        </div>

        <div id="map" class="flex-grow-1"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-16.5, -64.0], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marcadores = [];

        fetch('/mapa/reportes')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.length === 0) {
                    var ejemplos = [
                        { lat: -16.5,  lng: -68.15, titulo: 'Ejemplo La Paz',       categoria: 'Robo',      estado: 'Pendiente' },
                        { lat: -17.39, lng: -66.16, titulo: 'Ejemplo Cochabamba',   categoria: 'Accidente', estado: 'Pendiente' },
                        { lat: -17.78, lng: -63.18, titulo: 'Ejemplo Santa Cruz',   categoria: 'Vandalismo',estado: 'Pendiente' },
                    ];
                    ejemplos.forEach(function(m) { agregarMarcador(m); });
                } else {
                    data.forEach(function(m) { agregarMarcador(m); });
                }
            });

        function agregarMarcador(m) {
            var marker = L.marker([m.lat, m.lng])
                .addTo(map)
                .bindPopup('<b>' + m.titulo + '</b><br>Categoría: ' + m.categoria + '<br>Estado: ' + (m.estado || 'Pendiente'));
            marker.categoria = m.categoria || '';
            marker.estado = m.estado || '';
            marcadores.push(marker);
        }

        document.getElementById('btnFiltrar').addEventListener('click', function() {
            var catFiltro    = document.getElementById('filtroCategoria').value.toLowerCase();
            var estadoFiltro = document.getElementById('filtroEstado').value.toLowerCase();

            marcadores.forEach(function(marker) {
                var cat    = marker.categoria.toLowerCase();
                var estado = marker.estado.toLowerCase();

                var matchCat    = catFiltro === ''    || cat.includes(catFiltro);
                var matchEstado = estadoFiltro === '' || estado.includes(estadoFiltro);

                if (matchCat && matchEstado) {
                    marker.addTo(map);
                } else {
                    map.removeLayer(marker);
                }
            });
        });

        document.getElementById('btnLimpiar').addEventListener('click', function() {
            document.getElementById('filtroCategoria').value = '';
            document.getElementById('filtroEstado').value = '';
            marcadores.forEach(function(marker) { marker.addTo(map); });
        });
    </script>
</body>
</html>