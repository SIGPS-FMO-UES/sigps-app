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

1. **Configurar el archivo `.env`**:
   Puedes copiar la plantilla preparada para Docker:
   ```bash
   cp .env.dev.example .env
   ```
   *(Si usas PostgreSQL, asegúrate de mantener `DB_CONNECTION=pgsql`, `DB_HOST=postgres`).*

2. **Iniciar los contenedores en segundo plano**:
   ```bash
   docker compose -f docker-compose.dev.yml up -d
   ```
   *El entrypoint se encarga automáticamente de: verificar `.env`, generar `APP_KEY`, instalar dependencias, ejecutar migraciones y limpiar cachés.*

3. **Abrir en el navegador**:
   - Aplicación: [http://localhost:8000](http://localhost:8000)
   - Correos (Mailpit): [http://localhost:8025](http://localhost:8025)

---

### 💻 Comandos frecuentes en desarrollo

Ejecuta estos comandos desde la raíz del proyecto:

- **Ver logs en tiempo real**:
  ```bash
  docker compose -f docker-compose.dev.yml logs -f app
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

- **Detener y eliminar volúmenes (reset de base de datos)**:
  ```bash
  docker compose -f docker-compose.dev.yml down -v
  ```
