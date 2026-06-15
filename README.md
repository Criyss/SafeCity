# SafeCity — Plataforma de Reportes Urbanos

Sistema web de gestión de incidencias urbanas desarrollado con Laravel 11 y Bootstrap 5.

---

## Requisitos

- PHP >= 8.2
- Composer
- Git

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone <URL_DEL_REPO>
cd safecity

# 2. Instalar dependencias PHP
composer install

# 3. Copiar archivo de entorno
cp .env.example .env

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# 6. Iniciar el servidor
php artisan serve
```

Acceder en: http://127.0.0.1:8000

---

## Credenciales de prueba

| Rol            | Correo                 | Contraseña |
|----------------|------------------------|------------|
| Administrador  | admin@safecity.bo      | password   |
| Supervisor     | supervisor@safecity.bo | password   |
| Ciudadano      | ciudadano@safecity.bo  | password   |

---

## Estructura de ramas

| Rama                      | Responsable | Contenido                              |
|---------------------------|-------------|----------------------------------------|
| feature/auth              | Saúl        | Autenticación, middleware, roles       |
| feature/modelos-reporte   | Saúl        | Modelo Reporte, migración, relaciones  |
| feature/reportes          | Saúl        | Vistas index/show de reportes, filtros |
| feature/usuarios          | Estefanía   | CRUD de usuarios con buscador          |
| feature/categorias        | Estefanía   | CRUD de categorías, 16 categorías      |
| feature/estados           | Compartido  | Tabla estados_reporte, historial       |
| feature/mapas             | Keyra       | Mapa Leaflet, filtros                  |
| feature/dashboard         | Keyra       | Dashboard KPI y gráficas               |

---

## Funcionalidades implementadas

- Login con redirección según rol
- Registro de ciudadanos
- Middleware CheckRol para protección por roles
- CRUD completo de usuarios con buscador (solo admin)
- CRUD completo de 16 categorías (solo admin)
- Formulario de reporte con foto en base64 y coordenadas GPS
- Listado de reportes con filtros por departamento, categoría, gravedad y estado
- Ciudadano ve solo sus reportes; supervisor/admin ven todos
- Vista detalle del reporte con foto e historial de estados
- Cambio de estado con comentario (supervisor/admin)

---

## Roles del sistema

- **Administrador**: acceso total (usuarios, categorías, reportes, dashboard)
- **Supervisor**: gestión de reportes y dashboard
- **Ciudadano**: crea y consulta sus propios reportes
