{{-- Formulario de nuevo reporte con selector GPS en mapa Leaflet --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reporte - Safe City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #mapaGPS { height: 300px; border-radius: 8px; border: 1px solid #dee2e6; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3 align-items-center">
                <a href="/mapa" class="text-white text-decoration-none"><i class="bi bi-map me-1"></i>Mapa</a>
                @if(auth()->user()->rol !== 'ciudadano')
                    <a href="/dashboard" class="text-white text-decoration-none"><i class="bi bi-bar-chart me-1"></i>Dashboard</a>
                @endif
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

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header text-white" style="background-color: #1A3A5C;">
                        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nuevo Reporte de Incidencia</h5>
                    </div>
                    <div class="card-body">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        {{-- @csrf genera el token de seguridad que Laravel verifica al recibir el formulario --}}
                        <form action="{{ route('reportes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" placeholder="Ej: Bache en Av. Principal" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Categoría <span class="text-danger">*</span></label>
                                    {{-- Categorías dinámicas desde la BD --}}
                                    <select name="categoria_id" class="form-select" required>
                                        <option value="">Selecciona...</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($categorias->isEmpty())
                                        <small class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>No hay categorías. El administrador debe crearlas primero.</small>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Departamento <span class="text-danger">*</span></label>
                                    <select name="departamento" class="form-select" required>
                                        <option value="">Selecciona...</option>
                                        @foreach(['La Paz','Cochabamba','Santa Cruz','Oruro','Potosí','Chuquisaca','Tarija','Beni','Pando'] as $dep)
                                            <option value="{{ $dep }}" {{ old('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Gravedad <span class="text-danger">*</span></label>
                                <select name="gravedad" class="form-select" required>
                                    <option value="">Selecciona...</option>
                                    @foreach(['Baja','Media','Alta','Crítica'] as $g)
                                        <option value="{{ $g }}" {{ old('gravedad') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                                <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe la incidencia con detalle..." required>{{ old('descripcion') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-geo-alt me-1"></i>Ubicación GPS <span class="text-danger">*</span></label>
                                <p class="text-muted small mb-2">Haz clic en el mapa para marcar la ubicación exacta de la incidencia.</p>
                                <div id="mapaGPS"></div>
                                {{-- Campos ocultos que se llenan al hacer clic en el mapa --}}
                                <input type="hidden" name="latitud"  id="latitud"  value="{{ old('latitud') }}" required>
                                <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud') }}" required>
                                <div id="coordsDisplay" class="mt-2 text-muted small"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-camera me-1"></i>Foto de evidencia <span class="text-muted small">(opcional)</span></label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Máximo 10 MB. Formatos: jpg, png, webp.</small>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn text-white w-100 fw-bold" style="background-color: #1A3A5C;">
                                    <i class="bi bi-send me-1"></i>Enviar Reporte
                                </button>
                                <a href="/reportes" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-x-circle me-1"></i>Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inicializar mapa centrado en Bolivia
        const map = L.map('mapaGPS').setView([-16.5, -64.5], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = null;

        // Si hay coordenadas previas (al volver de validación), mostrar marcador
        const latPrev = document.getElementById('latitud').value;
        const lngPrev = document.getElementById('longitud').value;
        if (latPrev && lngPrev) {
            marker = L.marker([latPrev, lngPrev]).addTo(map);
            map.setView([latPrev, lngPrev], 13);
            document.getElementById('coordsDisplay').textContent = `Latitud: ${parseFloat(latPrev).toFixed(6)}, Longitud: ${parseFloat(lngPrev).toFixed(6)}`;
        }

        // Al hacer clic en el mapa, colocar marcador y guardar coordenadas
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            document.getElementById('latitud').value  = lat;
            document.getElementById('longitud').value = lng;
            document.getElementById('coordsDisplay').innerHTML =
                '<i class="bi bi-check-circle-fill text-success me-1"></i>Ubicación seleccionada: ' + lat + ', ' + lng;

            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map)
                      .bindPopup('Incidencia aquí').openPopup();
        });
    </script>
</body>
</html>
