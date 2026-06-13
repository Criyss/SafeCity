<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe City — Nueva Categoría</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark" style="background-color: #1A3A5C;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Safe City</a>
            <div class="d-flex gap-3">
                <a href="/categorias" class="text-white text-decoration-none">Categorías</a>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 600px;">
        <h4 class="fw-bold mb-4" style="color: #1A3A5C;">Nueva Categoría</h4>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="/categorias" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white w-100" style="background-color: #1A3A5C;">Guardar categoría</button>
                        <a href="/categorias" class="btn btn-outline-secondary w-100">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>