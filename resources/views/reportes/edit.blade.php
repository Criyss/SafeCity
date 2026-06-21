<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Editar Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shield-fill-check me-2"></i>Safe City</a>
            <div class="d-flex gap-3 align-items-center">
                <a href="/reportes" class="text-white text-decoration-none"><i class="bi bi-file-earmark-text me-1"></i>Reportes</a>
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
                        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Reporte</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="/reportes/{{ $reporte->id }}/update" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $reporte->titulo) }}" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Categoría <span class="text-danger">*</span></label>
                                    <select name="categoria_id" class="form-select" required>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}" {{ $reporte->categoria_id == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Departamento <span class="text-danger">*</span></label>
                                    <select name="departamento" class="form-select" required>
                                        @foreach(['La Paz','Cochabamba','Santa Cruz','Oruro','Potosí','Chuquisaca','Tarija','Beni','Pando'] as $dep)
                                            <option value="{{ $dep }}" {{ $reporte->departamento == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Gravedad <span class="text-danger">*</span></label>
                                <select name="gravedad" class="form-select" required>
                                    @foreach(['Baja','Media','Alta','Crítica'] as $g)
                                        <option value="{{ $g }}" {{ $reporte->gravedad == $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                                <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $reporte->descripcion) }}</textarea>
                            </div>
                            <div class="alert alert-info py-2">
                                <small><i class="bi bi-info-circle me-1"></i>La ubicación GPS y la foto no se pueden modificar.</small>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn text-white w-100 fw-bold" style="background-color: #1A3A5C;">
                                    <i class="bi bi-save me-1"></i>Guardar cambios
                                </button>
                                <a href="/reportes/{{ $reporte->id }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-x-circle me-1"></i>Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
