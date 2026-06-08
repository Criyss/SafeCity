<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Zonas Peligrosas de Bolivia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #map { height: calc(100vh - 110px); }
        #sidebar { width: 280px; min-width: 280px; background: white; box-shadow: 2px 0 5px rgba(0,0,0,0.1); padding: 20px; }
    </style>
</head>
<body>
    <!-- NAVBAR PÚBLICO -->
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/mapa-seguridad" class="text-white text-decoration-none">Mapa Público</a>
                <a href="/login" class="text-white text-decoration-none">Iniciar sesión</a>
                <a href="/register" class="text-white text-decoration-none">Registrarse</a>
            </div>
        </div>
    </nav>

    <!-- BANNER TURISTAS -->
    <div class="alert alert-info mb-0 rounded-0 py-2" style="background-color: #D6E4F0; border: none;">
        <div class="container-fluid">
            <strong>Mapa de Zonas Peligrosas de Bolivia</strong> — Consulta gratuita para turistas y visitantes sin necesidad de registro
        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="d-flex">
        <!-- SIDEBAR -->
        <div id="sidebar">
            <h5 class="fw-bold mb-3" style="color: #1A3A5C;">Tipo de Riesgo</h5>
            <div class="d-grid gap-2">
                <button class="btn text-white fw-bold" style="background-color: #1A3A5C;" onclick="filtrar('')">Ver todos</button>
                <button class="btn text-white fw-bold" style="background-color: #C0392B;" onclick="filtrar('Robos y Asaltos')">Robos y Asaltos</button>
                <button class="btn text-white fw-bold" style="background-color: #E67E22;" onclick="filtrar('Zonas Inseguras')">Zonas Inseguras</button>
                <button class="btn text-white fw-bold" style="background-color: #922B21;" onclick="filtrar('Emergencias')">Emergencias</button>
            </div>
            <hr>
            <small class="text-muted">Datos actualizados en tiempo real. Sin datos personales visibles.</small>
        </div>

        <!-- MAPA -->
        <div id="map" class="flex-grow-1"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <script>
        var map = L.map('map').setView([-16.5, -64.0], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var todosLosPuntos = [
            {lat: -16.5, lng: -68.15, titulo: 'Zona de riesgo La Paz', tipo: 'Robos y Asaltos'},
            {lat: -17.39, lng: -66.16, titulo: 'Zona insegura Cochabamba', tipo: 'Zonas Inseguras'},
            {lat: -17.78, lng: -63.18, titulo: 'Emergencia Santa Cruz', tipo: 'Emergencias'},
            {lat: -19.58, lng: -65.75, titulo: 'Zona de riesgo Potosí', tipo: 'Robos y Asaltos'},
            {lat: -21.53, lng: -64.73, titulo: 'Zona insegura Tarija', tipo: 'Zonas Inseguras'},
        ];

        var colores = {
            'Robos y Asaltos': '#C0392B',
            'Zonas Inseguras': '#E67E22',
            'Emergencias': '#922B21'
        };

        var marcadores = [];

        function crearMarcador(punto) {
            var color = colores[punto.tipo] || '#C0392B';
            var icono = L.divIcon({
                html: '<div style="background:' + color + ';width:16px;height:16px;border-radius:50%;border:2px solid white;"></div>',
                iconSize: [16, 16],
                className: ''
            });
            return L.marker([punto.lat, punto.lng], {icon: icono})
                .bindPopup('<b>' + punto.titulo + '</b><br>Tipo: ' + punto.tipo);
        }

        todosLosPuntos.forEach(function(p) {
            var m = crearMarcador(p);
            m.tipo = p.tipo;
            m.addTo(map);
            marcadores.push(m);
        });

        // Heatmap
        var heatData = todosLosPuntos.map(function(p) { return [p.lat, p.lng, 0.8]; });
        L.heatLayer(heatData, {radius: 40, blur: 25, maxZoom: 10}).addTo(map);

        function filtrar(tipo) {
            marcadores.forEach(function(m) {
                if (tipo === '' || m.tipo === tipo) {
                    m.addTo(map);
                } else {
                    map.removeLayer(m);
                }
            });
        }
    </script>
</body>
</html>