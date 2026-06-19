{{-- Mapa público para turistas sin login — Hecho por Keyra --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Mapa de Zonas Peligrosas de Bolivia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        #map     { height: calc(100vh - 110px); }
        #sidebar { width: 250px; min-width: 250px; background: white; box-shadow: 2px 0 5px rgba(0,0,0,0.1); padding: 20px; overflow-y: auto; max-height: calc(100vh - 110px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3">
                <a href="/mapa-seguridad" class="text-white text-decoration-none">Mapa Público</a>
                <a href="/login"    class="btn btn-outline-light btn-sm">Iniciar sesión</a>
                <a href="/register" class="btn btn-light btn-sm" style="color: #1A3A5C;">Registrarse</a>
            </div>
        </div>
    </nav>

    <div class="py-2 px-3" style="background-color: #D6E4F0; border-bottom: 1px solid #AED6F1;">
        <small><strong>📍 Mapa de Incidencias de Bolivia</strong> — Consulta gratuita para turistas. Sin necesidad de registro.</small>
    </div>

    <div class="d-flex">
        <div id="sidebar">
            <h6 class="fw-bold mb-3" style="color: #1A3A5C;">Filtros</h6>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Departamento</label>
                <select class="form-select form-select-sm" id="filtroDep">
                    <option value="">Todos</option>
                    @foreach(['La Paz','Cochabamba','Santa Cruz','Oruro','Potosí','Chuquisaca','Tarija','Beni','Pando'] as $dep)
                        <option>{{ $dep }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Estado</label>
                <select class="form-select form-select-sm" id="filtroEst">
                    <option value="">Todos</option>
                    <option>Pendiente</option>
                    <option>En revisión</option>
                    <option>En proceso</option>
                    <option>Resuelto</option>
                    <option>Cerrado</option>
                </select>
            </div>

            <button onclick="aplicarFiltros()" class="btn w-100 btn-sm text-white mb-2" style="background-color: #1A3A5C;">Aplicar</button>
            <button onclick="limpiarFiltros()" class="btn w-100 btn-sm mb-3" style="border:1px solid #1A3A5C; color:#1A3A5C;">Limpiar</button>

            <div id="contador" class="text-muted small text-center mb-2"></div>

            <hr>
            <p class="small fw-semibold mb-2">Nivel de Gravedad:</p>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#27AE60;"></div><small>Baja</small></div>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#F39C12;"></div><small>Media</small></div>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#E67E22;"></div><small>Alta</small></div>
            <div class="d-flex align-items-center gap-2 mb-1"><div style="width:12px;height:12px;border-radius:50%;background:#C0392B;"></div><small>Crítica</small></div>

            <hr>
            <div class="p-2 rounded" style="background:#EBF5FB; border:1px solid #AED6F1;">
                <small class="text-muted"><strong>¿Quieres reportar?</strong><br><a href="/register">Regístrate gratis</a></small>
            </div>
        </div>

        <div id="map" class="flex-grow-1"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <script>
        var mapa = L.map('map').setView([-16.5, -64.0], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);

        var todosLosReportes = [];
        var marcadores = [];
        var heatLayer = null;

        var colores = { 'Baja': '#27AE60', 'Media': '#F39C12', 'Alta': '#E67E22', 'Critica': '#C0392B' };

        function crearIcono(gravedad) {
            var color = colores[gravedad] || '#7F8C8D';
            return L.divIcon({
                className: '',
                html: '<div style="width:14px;height:14px;border-radius:50%;background:' + color + ';border:2px solid white;box-shadow:0 1px 3px rgba(0,0,0,0.4);"></div>',
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });
        }

        function cargarMapa(datos) {
            marcadores.forEach(function(m){ mapa.removeLayer(m); });
            marcadores = [];
            if (heatLayer) { mapa.removeLayer(heatLayer); heatLayer = null; }

            var heatData = [];
            datos.forEach(function(r) {
                if (!r.latitud || !r.longitud) return;
                var lat = parseFloat(r.latitud);
                var lng = parseFloat(r.longitud);
                var grav = r.gravedad || 'Baja';
                var m = L.marker([lat, lng], { icon: crearIcono(grav) });
                m.bindPopup(
                    '<strong>' + r.titulo + '</strong><br>' +
                    'Categoría: ' + (r.categoria || '—') + '<br>' +
                    'Departamento: ' + (r.departamento || '—') + '<br>' +
                    'Gravedad: ' + grav + '<br>' +
                    'Estado: ' + (r.estado || '—')
                );
                m.addTo(mapa);
                marcadores.push(m);
                heatData.push([lat, lng, 0.6]);
            });

            if (heatData.length > 0) {
                heatLayer = L.heatLayer(heatData, { radius: 25, blur: 15, maxZoom: 17 }).addTo(mapa);
            }

            document.getElementById('contador').textContent = datos.length + ' reporte(s) mostrado(s)';
        }

        function aplicarFiltros() {
            var dep = document.getElementById('filtroDep').value;
            var est = document.getElementById('filtroEst').value;
            var filtrados = todosLosReportes.filter(function(r) {
                return (!dep || r.departamento === dep) && (!est || r.estado === est);
            });
            cargarMapa(filtrados);
        }

        function limpiarFiltros() {
            document.getElementById('filtroDep').value = '';
            document.getElementById('filtroEst').value = '';
            cargarMapa(todosLosReportes);
        }

        // Cargar datos del endpoint público
        fetch('/mapa-seguridad/reportes')
            .then(function(r){ return r.json(); })
            .then(function(datos){
                todosLosReportes = datos;
                cargarMapa(datos);
            })
            .catch(function(){ document.getElementById('contador').textContent = 'Error al cargar datos'; });
    </script>
</body>
</html>
