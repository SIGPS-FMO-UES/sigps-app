# SIGPS · Sistema Integral de Gestión de Proyección Social

Aplicación de gestión institucional de la **Unidad de Proyección Social (UPS)** de la **Facultad Multidisciplinaria Oriental de la Universidad de El Salvador (FMO-UES)**. El sistema respalda la administración de expedientes y proyectos de servicio social, desde su recepción y seguimiento hasta la gestión de memorias y certificaciones.

## Alcance

SIGPS es el backoffice de la UPS (SYS-01). Incluye:

- Gestión de estudiantes, proyectos, expedientes y documentos.
- Seguimiento de fases, plazos, prórrogas y procesos extraordinarios.
- Recepción, revisión y entrega de memorias.
- Administración de usuarios, permisos, catálogos y plantillas institucionales.
- Estadísticas y exportación de información.
- API versionada para consultas e integración con sistemas de la facultad.

La documentación de los endpoints se encuentra en [`docs/API_PUBLICA.md`](docs/API_PUBLICA.md). La lógica de negocio está descrita en [`docs/LOGICA_NEGOCIO_BPMN.md`](docs/LOGICA_NEGOCIO_BPMN.md).

## Tecnologías

| Componente | Tecnología |
| --- | --- |
| Backend | PHP 8.3+ y Laravel 13 |
| Interfaz | Blade, Tailwind CSS 4 y Vite |
| Base de datos | PostgreSQL 16 (recomendada para desarrollo con Docker) o SQLite |
| Autenticación de API | Laravel Sanctum |
| Entorno de desarrollo | Docker Compose, Nginx, Redis y Mailpit |

## Requisitos

Para usar el entorno recomendado, instala **Docker** y **Docker Compose v2**. No necesitas instalar PHP, Composer, Node.js ni PostgreSQL en el equipo anfitrión: los servicios de desarrollo se ejecutan en contenedores.

## Inicio rápido con Docker

Ejecuta los siguientes comandos desde la raíz del repositorio.

1. Crea el archivo de entorno:

   ```bash
   cp .env.dev.example .env
   ```

   En PowerShell puedes usar `Copy-Item .env.dev.example .env`. Si ya tienes un archivo `.env`, conserva tu configuración y no lo sobrescribas.

2. Construye e inicia la aplicación y sus servicios:

   ```bash
   docker compose -f docker-compose.dev.yml up -d --build
   ```

   En el primer inicio se instalan las dependencias, se genera `APP_KEY` si hace falta y se ejecutan las migraciones. La aplicación usa PostgreSQL según `.env.dev.example`.

3. Abre la aplicación y las herramientas de desarrollo:

   | Servicio | Dirección |
   | --- | --- |
   | Aplicación | [http://localhost](http://localhost) |
   | Interfaz de Vite con recarga en vivo | [http://localhost:5173](http://localhost:5173) |
   | Mailpit, bandeja de correo local | [http://localhost:8025](http://localhost:8025) |
   | PostgreSQL | `localhost:5432` |
   | Redis | `localhost:6379` |

   Los puertos pueden cambiarse en `.env` mediante `APP_PORT`, `VITE_PORT`, `FORWARD_DB_PORT`, `FORWARD_REDIS_PORT` y `FORWARD_MAILPIT_DASHBOARD_PORT`.

## Comandos útiles

```bash
# Ver el estado de los servicios
docker compose -f docker-compose.dev.yml ps

# Consultar los logs
docker compose -f docker-compose.dev.yml logs -f

# Ejecutar migraciones
docker compose -f docker-compose.dev.yml exec app php artisan migrate

# Ejecutar pruebas
docker compose -f docker-compose.dev.yml exec app php artisan test

# Abrir Tinker
docker compose -f docker-compose.dev.yml exec app php artisan tinker

# Compilar assets para producción
docker compose -f docker-compose.dev.yml exec vite npm run build

# Detener los servicios y conservar los datos
docker compose -f docker-compose.dev.yml down
```

`docker compose down` conserva los volúmenes de PostgreSQL, Redis y dependencias. Para eliminar también esos datos se puede usar `docker compose down -v`; esto borra la base de datos local y los volúmenes asociados.

## Estructura del repositorio

| Ruta | Contenido |
| --- | --- |
| `app/` | Controladores, modelos, middleware y servicios de la aplicación |
| `routes/` | Rutas web y API |
| `resources/` | Vistas Blade, estilos y recursos del frontend |
| `database/` | Migraciones, seeders y fábricas |
| `docs/` | Documentación de API y procesos de negocio |
| `docker/` | Configuración del entorno de desarrollo |

## API

La API usa el prefijo `/api/v1`. Incluye endpoints públicos para consultar formatos oficiales y catálogos académicos, además de endpoints protegidos para las operaciones internas de la UPS. Las credenciales y permisos dependen del endpoint.

Consulta [`docs/API_PUBLICA.md`](docs/API_PUBLICA.md) para conocer las rutas, parámetros y ejemplos de integración. El endpoint de disponibilidad es `GET /api/v1/ping`.

## Despliegue

El archivo `docker-compose.yml` está preparado para desplegar el contenedor de la aplicación en una red externa de Dokploy. El archivo `docker-compose.dev.yml` es para desarrollo local; no debe utilizarse como configuración de producción.

## Contribuciones

Antes de proponer cambios, revisa las convenciones del repositorio y documenta cualquier ajuste que afecte la API o los procesos operativos de la UPS. Ejecuta las pruebas pertinentes antes de enviar tus cambios.
