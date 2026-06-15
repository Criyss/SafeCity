# SafeCity

Plataforma de geolocalización de incidencias urbanas en Bolivia. Permite a ciudadanos reportar incidencias (robos, baches, alumbrado, etc.) geolocalizadas en un mapa interactivo, y a supervisores y administradores gestionar el seguimiento de los reportes.

## Tecnologías

- PHP 8.2
- Laravel 12
- MySQL
- Bootstrap 5
- Leaflet.js (mapas)
- XAMPP

## Requisitos previos

- XAMPP con PHP 8.2 y MySQL
- Composer
- Git

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/SafeCity.git
cd SafeCity
```

O copiar la carpeta directamente en `C:\xampp\htdocs\SafeCity`.

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el archivo de entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con los datos de la base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=safecity
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Crear la base de datos

Abrir phpMyAdmin en `http://localhost/phpmyadmin` y crear una base de datos llamada `safecity`.

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto crea todas las tablas y carga los datos iniciales (usuarios de prueba, 16 categorías predefinidas).

### 6. Iniciar el servidor

Con XAMPP corriendo, acceder directamente a:

```
http://localhost/SafeCity/public
```

O usando el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

Y acceder a `http://localhost:8000`.

## Credenciales de prueba

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Administrador | admin@safecity.bo | password |
| Supervisor | supervisor@safecity.bo | password |
| Ciudadano | ciudadano@safecity.bo | password |

## URLs principales

| URL | Descripción | Acceso |
|-----|-------------|--------|
| `/login` | Inicio de sesión | Público |
| `/register` | Registro de ciudadano | Público |
| `/mapa-seguridad` | Mapa público de incidencias | Público |
| `/dashboard` | Panel de estadísticas | Admin / Supervisor |
| `/reportes` | Lista de reportes | Todos los usuarios |
| `/reportes/crear` | Crear nuevo reporte | Todos los usuarios |
| `/mapa` | Mapa con filtros (autenticado) | Todos los usuarios |
| `/categorias` | Gestión de categorías | Administrador |
| `/usuarios` | Gestión de usuarios | Administrador |

## Roles del sistema

- **Administrador**: acceso total — gestiona usuarios, categorías, reportes y puede cambiar estados.
- **Supervisor**: visualiza el dashboard y puede cambiar el estado de los reportes.
- **Ciudadano**: crea reportes y consulta el estado de los suyos.

## Estructura principal

```
app/
  Http/Controllers/
    AuthController.php       # Login, logout, registro
    DashboardController.php  # Estadísticas generales
    ReporteController.php    # CRUD de reportes y cambio de estado
    CategoriaController.php  # CRUD de categorías
    UserController.php       # CRUD de usuarios
    MapaController.php       # Mapa y endpoints JSON
  Models/
    User.php
    Reporte.php
    Categoria.php
    EstadoReporte.php
resources/views/
  auth/           # Login y registro
  dashboard/      # Panel principal
  reportes/       # Lista, detalle y creación de reportes
  categorias/     # CRUD de categorías
  users/          # CRUD de usuarios
  maps/           # Vista del mapa
routes/
  web.php         # Todas las rutas con middleware auth y rol
```
