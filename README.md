# SIGPS - Sistema Integral de Gestión de Proyección Social

Sistema Integral de Gestión de Proyección Social (SIGPS) para la Unidad de Proyección Social (UPS) de la Facultad Multidisciplinaria Oriental - UES.

Desarrollado con **Laravel 13**, **PostgreSQL 16** y **Tailwind CSS**.

---

## 🐳 Entorno de Desarrollo Local con Docker

El proyecto incluye dos configuraciones de Docker:
- `docker-compose.yml` / `dockerfile`: Destinado a despliegues en producción (Dokploy / red externa).
- `docker-compose.dev.yml` / `docker/dev/`: Diseñado para **desarrollo y pruebas locales** con recarga en vivo de código y assets.

### 🚀 Servicios disponibles en desarrollo

| Servicio | Descripción | Puerto Host / URL |
| :--- | :--- | :--- |
| **`app`** | Laravel (PHP 8.4-FPM + Nginx) | [http://localhost:8000](http://localhost:8000) |
| **`vite`** | Servidor de desarrollo Vite HMR (Hot Module Replacement) | [http://localhost:5173](http://localhost:5173) |
| **`postgres`** | Base de datos PostgreSQL 16 | `localhost:5432` |
| **`mailpit`** | Servidor SMTP y bandeja de prueba de correos | Web UI: [http://localhost:8025](http://localhost:8025) (SMTP: `1025`) |
| **`redis`** | Caché y colas en memoria | `localhost:6379` |

---

### 🛠️ Puesta en marcha rápida

Ejecuta los comandos desde la carpeta raíz del proyecto, donde está `docker-compose.dev.yml`.

1. **Preparar `.env` la primera vez**. En PowerShell:
   ```powershell
   Copy-Item .env.dev.example .env
   ```
   Si ya tienes un `.env`, consérvalo y no lo sobrescribas. La plantilla configura PostgreSQL con `DB_CONNECTION=pgsql` y `DB_HOST=postgres`.

2. **Construir e iniciar los servicios**:
   ```powershell
   docker compose -f docker-compose.dev.yml up -d --build
   ```
   La primera ejecución construye la imagen y puede tardar unos minutos. El contenedor `app` instala las dependencias de PHP y genera `APP_KEY` si hace falta. `vite` instala las dependencias de JavaScript e inicia la recarga en vivo.

3. **Comprobar el arranque**:
   ```powershell
   docker compose -f docker-compose.dev.yml ps
   docker compose -f docker-compose.dev.yml logs -f
   ```
   Presiona `Ctrl+C` para salir de los logs; esto no detiene los servicios. Si la aplicación reporta que faltan tablas, ejecuta:
   ```powershell
   docker compose -f docker-compose.dev.yml exec app php artisan migrate
   ```

4. **Abrir en el navegador**:
   - Aplicación: [http://localhost:8000](http://localhost:8000)
   - Correos de prueba (Mailpit): [http://localhost:8025](http://localhost:8025)

---
### 💻 Comandos frecuentes en desarrollo

Ejecuta estos comandos desde la raíz del proyecto:

- **Ver logs en tiempo real**:
  ```bash
  docker compose -f docker-compose.dev.yml logs -f
  ```

- **Ejecutar comandos de Artisan**:
  ```bash
  docker compose -f docker-compose.dev.yml exec app php artisan migrate
  docker compose -f docker-compose.dev.yml exec app php artisan tinker
  ```

- **Ejecutar pruebas (tests)**:
  ```bash
  docker compose -f docker-compose.dev.yml exec app php artisan test
  ```

- **Instalar o actualizar paquetes con Composer**:
  ```bash
  docker compose -f docker-compose.dev.yml exec app composer require <vendor/paquete>
  ```

- **Ejecutar comandos de Node / NPM**:
  ```bash
  docker compose -f docker-compose.dev.yml exec app npm install
  docker compose -f docker-compose.dev.yml exec app npm run build
  ```

- **Detener los contenedores**:
  ```bash
  docker compose -f docker-compose.dev.yml down
  ```

  Esto conserva los datos de PostgreSQL y los volúmenes de dependencias. Para volver a iniciar, ejecuta el comando `up -d` de arriba.

> **Nota:** `docker compose down -v` también elimina los volúmenes de PostgreSQL, Redis y Composer; úsalo solo si quieres borrar esos datos y reconstruir el entorno desde cero.
