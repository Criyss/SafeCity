<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reporte - Safe City</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm max-w-md mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Reportar Nueva Incidencia</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="{{ route('reportes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Título del Reporte</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Categoría</label>
                    <select name="categoria_id" class="form-select" required>
                        <option value="">Selecciona una categoría...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Descripción detallada</label>
                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label>Evidencia Fotográfica</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>

                <input type="hidden" name="latitud" id="latitud" value="-17.3923">
<input type="hidden" name="longitud" id="longitud" value="-66.1536">

<div class="d-grid gap-2">
    <button type="submit" class="btn btn-primary" id="btnSubmit">
        Enviar Reporte (Modo Prueba)
    </button>
</div>
            </form>
        </div>
    </div>
</div>

<script>
function obtenerUbicacion() {
    const status = document.getElementById('gpsStatus');
    const btnSubmit = document.getElementById('btnSubmit');

    if (!navigator.geolocation) {
        status.textContent = "La geolocalización no es soportada por tu navegador.";
        return;
    }

    status.textContent = "Localizando...";
    status.className = "text-warning mt-2 d-block text-center";

    navigator.geolocation.getCurrentPosition(
        (position) => {
            document.getElementById('latitud').value = position.coords.latitude;
            document.getElementById('longitud').value = position.coords.longitude;
            status.textContent = "✅ Ubicación capturada con éxito.";
            status.className = "text-success mt-2 d-block text-center";
            btnSubmit.disabled = false;
        },
        (error) => {
            status.textContent = "❌ Error al obtener ubicación. Asegúrate de dar permisos.";
            status.className = "text-danger mt-2 d-block text-center";
        }
    );
}
</script>
</body>
</html>